<?php

namespace App\Http\Controllers;

use App\Models\Penawaran;
use App\Models\ItemPenawaran;
use App\Models\Client;
use App\Models\Material;
use App\Imports\BoqImport;
use App\Exports\BoqTemplateExport;
use App\Http\Requests\CopyItemsFromPenawaranRequest;
use App\Http\Requests\GetItemPriceTrendRequest;
use App\Http\Requests\FindSimilarPenawaranRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PenawaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Calculate KPI data sebelum pagination
        $allPenawaran = Penawaran::all();
        $totalPenawaran = $allPenawaran->count();
        $totalValue = $allPenawaran->sum(function($item) {
            return $item->grand_total_with_ppn ?? ($item->grand_total * 1.11);
        });
        $pendingPenawaran = $allPenawaran->where('status', 'draft')->count();
        
        // Data dengan pagination
        $penawaran = Penawaran::with('client')->orderBy('created_at', 'DESC')->paginate(15);

        return view('penawaran.index', compact('penawaran', 'totalPenawaran', 'totalValue', 'pendingPenawaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $materials = Material::with('supplier')->get();
        $noPenawaran = Penawaran::generateNoPenawaran();
        
        return view('penawaran.create', compact('clients', 'materials', 'noPenawaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_penawaran' => 'required|string|unique:penawaran',
            'client_id' => 'required|exists:clients,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,disetujui,ditolak,dibatalkan',
            'wilayah' => 'nullable|string|max:255',
            'jenis_pekerjaan' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_asli' => 'required|numeric|min:0',
            'items.*.persentase_margin' => 'required|numeric|min:0|max:100',
        ]);

        // Calculate totals
        $totalBiaya = 0;
        $totalMargin = 0;

        $penawaran = Penawaran::create([
            'no_penawaran' => $validated['no_penawaran'],
            'client_id' => $validated['client_id'],
            'tanggal' => $validated['tanggal'],
            'status' => $validated['status'],
            'wilayah' => $validated['wilayah'],
            'jenis_pekerjaan' => $validated['jenis_pekerjaan'],
            'total_biaya' => 0, // Will be updated
            'total_margin' => 0, // Will be updated
        ]);

        // Store items and calculate totals
        foreach ($validated['items'] as $item) {
            // Kalkulasi harga jual = harga asli + (harga asli * margin%)
            $hargaAsli = $item['harga_asli'];
            $margin = $item['persentase_margin'];
            $hargaJual = $hargaAsli + ($hargaAsli * $margin / 100);
            
            $totalBiayaAsli = $hargaAsli * $item['jumlah'];
            $totalHargaJual = $hargaJual * $item['jumlah'];
            $marginValue = $totalHargaJual - $totalBiayaAsli;

            ItemPenawaran::create([
                'penawaran_id' => $penawaran->id,
                'material_id' => $item['material_id'],
                'jumlah' => $item['jumlah'],
                'harga_asli' => $hargaAsli,
                'persentase_margin' => $margin,
                'harga_jual' => $hargaJual,
            ]);

            $totalBiaya += $totalBiayaAsli;
            $totalMargin += $marginValue;
        }

        // Update totals
        $grandTotal = $totalBiaya + $totalMargin;
        $ppn = $grandTotal * 0.11;
        $grandTotalWithPpn = $grandTotal * 1.11;
        
        $penawaran->update([
            'total_biaya' => $totalBiaya,
            'total_margin' => $totalMargin,
            'ppn' => $ppn,
            'grand_total_with_ppn' => $grandTotalWithPpn,
        ]);

        return redirect()->route('penawaran.show', $penawaran->id)->with('success', 'Penawaran berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penawaran $penawaran)
    {
        $penawaran->load('client', 'items.material');
        return view('penawaran.show', compact('penawaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penawaran $penawaran)
    {
        $penawaran->load('client', 'items');
        $clients = Client::all();
        $materials = Material::with('supplier')->get();
        
        return view('penawaran.edit', compact('penawaran', 'clients', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     * 
     * NOTE: Inventory NOT reduced here because:
     * - Client hasn't purchased goods yet (penawaran = estimate only)
     * - Stock will be tracked when goods actually received from supplier
     * - Focus here: Budget tracking (estimate vs actual spending via Pengeluaran)
     */
    public function update(Request $request, Penawaran $penawaran)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,disetujui,ditolak,dibatalkan',
            'wilayah' => 'nullable|string|max:255',
            'jenis_pekerjaan' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_asli' => 'required|numeric|min:0',
            'items.*.persentase_margin' => 'required|numeric|min:0|max:100',
        ]);

        // Delete old items
        ItemPenawaran::where('penawaran_id', $penawaran->id)->delete();

        // Calculate new totals
        $totalBiaya = 0;
        $totalMargin = 0;

        // Store new items and calculate totals
        foreach ($validated['items'] as $item) {
            // Kalkulasi harga jual = harga asli + (harga asli * margin%)
            $hargaAsli = $item['harga_asli'];
            $margin = $item['persentase_margin'];
            $hargaJual = $hargaAsli + ($hargaAsli * $margin / 100);
            
            $totalBiayaAsli = $hargaAsli * $item['jumlah'];
            $totalHargaJual = $hargaJual * $item['jumlah'];
            $marginValue = $totalHargaJual - $totalBiayaAsli;

            ItemPenawaran::create([
                'penawaran_id' => $penawaran->id,
                'material_id' => $item['material_id'],
                'jumlah' => $item['jumlah'],
                'harga_asli' => $hargaAsli,
                'persentase_margin' => $margin,
                'harga_jual' => $hargaJual,
            ]);

            $totalBiaya += $totalBiayaAsli;
            $totalMargin += $marginValue;
        }

        // Update penawaran with budget figures (no inventory reduction)
        $grandTotal = $totalBiaya + $totalMargin;
        $ppn = $grandTotal * 0.11;
        $grandTotalWithPpn = $grandTotal * 1.11;
        
        $penawaran->update([
            'client_id' => $validated['client_id'],
            'tanggal' => $validated['tanggal'],
            'status' => $validated['status'],
            'wilayah' => $validated['wilayah'],
            'jenis_pekerjaan' => $validated['jenis_pekerjaan'],
            'total_biaya' => $totalBiaya,
            'total_margin' => $totalMargin,
            'ppn' => $ppn,
            'grand_total_with_ppn' => $grandTotalWithPpn,
        ]);

        $message = 'Penawaran berhasil diubah';
        if ($validated['status'] === 'disetujui') {
            $message .= ' dan disetujui. Budget reference: Rp ' . number_format($grandTotalWithPpn, 0, ',', '.');
        }

        return redirect()->route('penawaran.show', $penawaran->id)->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penawaran $penawaran)
    {
        $penawaran->delete();
        return redirect()->route('penawaran.index')->with('success', 'Penawaran berhasil dihapus');
    }

    /**
     * Update status penawaran
     * 
     * NOTE: Only updates status and budget reference.
     * Inventory tracking happens when goods received from supplier (separate flow).
     */
    public function updateStatus(Request $request, Penawaran $penawaran)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,disetujui,ditolak,dibatalkan',
        ]);

        $oldStatus = $penawaran->status;
        $newStatus = $validated['status'];

        // Just update status - no inventory manipulation
        // Budget tracking: penawaran.grand_total_with_ppn is reference for project budget
        $penawaran->update([
            'status' => $newStatus,
        ]);

        Log::info('Penawaran status updated', [
            'penawaran_id' => $penawaran->id,
            'no_penawaran' => $penawaran->no_penawaran,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'budget_reference' => $penawaran->grand_total_with_ppn,
            'updated_by' => auth()->id() ?? 1,
        ]);

        return redirect()->route('penawaran.show', $penawaran->id)
            ->with('success', 'Status penawaran berhasil diubah menjadi ' . $penawaran->getStatusLabel());
    }

    /**
     * DEPRECATED: Inventory methods below are NO LONGER CALLED
     * 
     * REASON: Business model clarification shows:
     * - Penawaran (BoQ) = Budget ESTIMATE, not actual goods purchase
     * - Client doesn't have stock yet (they'll order from supplier later)
     * - Inventory tracking will happen at different stage (goods receipt from supplier)
     * 
     * Currently, focus is on BUDGET TRACKING:
     * - penawaran.grand_total_with_ppn = Budget reference
     * - Pengeluaran (actual spending) = compared against this budget
     * - Determine overrun/savings in Proyek
     * 
     * These methods kept for FUTURE reference when goods receipt module is added
     * If needed, modify to track goods RECEIVED from supplier, not penawaran approval
     */
    
    /**
     * UNUSED: reduceInventory() - stock reduction no longer triggered by penawaran approval
     * 
     * @deprecated See comment above
     */
    // private function reduceInventory(Penawaran $penawaran) { ... }

    /**
     * UNUSED: restoreInventory() - stock restoration no longer needed
     * 
     * @deprecated See comment above  
     */
    // private function restoreInventory(Penawaran $penawaran, $newStatus = 'dibatalkan') { ... }

    /**
     * Export template Excel BoQ untuk user
     */
    public function exportBoqTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('BoQ Template');
        
        // Header row
        $headers = ['kode', 'nama', 'satuan', 'jumlah', 'harga_satuan', 'persentase_margin'];
        $sheet->fromArray($headers, NULL, 'A1');
        
        // Sample data
        $sampleData = [
            ['MAT001', 'Batu Bata', 'pcs', 50000, 2000, 10],
            ['MAT002', 'Semen', 'kg', 20000, 1500, 10],
            ['JAR001', 'Jasa Tukang', 'hari', 30, 350000, 15],
        ];
        $sheet->fromArray($sampleData, NULL, 'A2');
        
        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);
        
        // Auto size columns
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(18);
        
        // Create writer
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Template_BoQ_Penawaran.xlsx';
        
        // Output to browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    /**
     * Upload dan preview Excel BoQ sebelum analisis AI
     * 
     * Flow baru:
     * 1. User upload Excel BoQ
     * 2. Baca Grand Total (belum simpan ke database)
     * 3. Tampilkan tombol "Analisis Prediksi AI"
     */
    /**
     * Preview import BoQ Excel file
     * Enhanced validation: file size, type, extension, integrity check
     */
    public function uploadBoqPreview(Request $request)
    {
        $startTime = microtime(true);
        
        // Enhanced validation with custom rules
        $validated = $request->validate([
            'boq_file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:5120', // 5MB
                function ($attribute, $value, $fail) {
                    // Check if file is actually a valid Excel file
                    if (function_exists('finfo_open')) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mimeType = finfo_file($finfo, $value->getRealPath());
                        finfo_close($finfo);
                        
                        $validMimes = [
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/x-msexcel',
                        ];
                        
                        if (!in_array($mimeType, $validMimes)) {
                            $fail('File harus berupa Excel (.xlsx atau .xls) yang valid.');
                        }
                    }
                },
            ],
        ]);

        $file = $request->file('boq_file');
        
        Log::info('BoQ file upload preview started', [
            'user_id' => auth()->id(),
            'filename' => $file->getClientOriginalName(),
            'file_size_bytes' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ]);
        
        try {
            // Additional validation: check file size
            if ($file->getSize() > 5242880) { // 5MB in bytes
                throw new \Exception('Ukuran file terlalu besar. Maksimal 5MB.');
            }
            
            if ($file->getSize() === 0) {
                throw new \Exception('File tidak boleh kosong.');
            }
            
            $import = new BoqImport(previewMode: true);
            $import->import($file);

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            Log::info('BoQ file preview processed successfully', [
                'user_id' => auth()->id(),
                'item_count' => $import->success,
                'error_count' => count($import->errors),
                'grand_total' => $import->grandTotal,
                'execution_time_ms' => $executionTime
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $import->items,
                    'item_count' => $import->success,
                    'subtotal' => $import->totalBiaya + $import->totalMargin,
                    'ppn_11_percent' => ($import->totalBiaya + $import->totalMargin) * 0.11,
                    'grand_total' => $import->grandTotal,
                    'error_count' => count($import->errors),
                    'errors' => $import->errors,
                ]
            ]);
        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::warning('BoQ file preview processing failed', [
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'file_size_bytes' => $file->getSize(),
                'execution_time_ms' => $executionTime
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'items' => [],
                    'item_count' => 0,
                    'subtotal' => 0,
                    'ppn_11_percent' => 0,
                    'grand_total' => 0,
                    'error_count' => 1,
                    'errors' => ['Error membaca file: ' . $e->getMessage()],
                ]
            ], 422);
        }
    }

    /**
     * Store penawaran dari BoQ import
     * Import mode: actually save items to database
     */
    public function storeFromBoq(Request $request)
    {
        $startTime = microtime(true);
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'tanggal_penawaran' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.kode' => 'required|string',
            'items.*.nama' => 'required|string',
            'items.*.satuan' => 'required|string',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_asli' => 'required|numeric|min:0',
            'items.*.persentase_margin' => 'required|numeric|min:0|max:100',
        ]);

        Log::info('Starting BoQ import and penawaran creation', [
            'user_id' => auth()->id(),
            'client_id' => $validated['client_id'],
            'items_count' => count($validated['items'])
        ]);

        try {
            // Generate no_penawaran
            $noPenawaran = Penawaran::generateNoPenawaran();

            // Calculate totals
            $totalBiaya = 0;
            $totalMargin = 0;

            // Create penawaran dengan status DRAFT
            $penawaran = Penawaran::create([
                'no_penawaran' => $noPenawaran,
                'client_id' => $validated['client_id'],
                'tanggal' => $validated['tanggal_penawaran'],
                'status' => 'draft',
                'ai_status' => 'pending',
            ]);

            // Create items dan hitung total
            foreach ($validated['items'] as $item) {
                $hargaAsli = (float)$item['harga_asli'];
                $persentaseMargin = (float)$item['persentase_margin'];
                $jumlah = (int)$item['jumlah'];

                $marginPerUnit = $hargaAsli * ($persentaseMargin / 100);
                $hargaJual = $hargaAsli + $marginPerUnit;

                $totalBiayaItem = $hargaAsli * $jumlah;
                $totalMarginItem = $marginPerUnit * $jumlah;

                ItemPenawaran::create([
                    'penawaran_id' => $penawaran->id,
                    'nama' => $item['nama'],  // Simpan nama dari BoQ
                    'satuan' => $item['satuan'],  // Simpan satuan dari BoQ
                    'jumlah' => $jumlah,
                    'harga_asli' => $hargaAsli,
                    'persentase_margin' => $persentaseMargin,
                    'harga_jual' => $hargaJual,
                ]);

                $totalBiaya += $totalBiayaItem;
                $totalMargin += $totalMarginItem;
            }

            // Calculate PPN & grand total
            $subtotal = $totalBiaya + $totalMargin;
            $ppn = $subtotal * 0.11;
            $grandTotal = $subtotal + $ppn;

            // Update penawaran dengan final totals
            $penawaran->update([
                'total_biaya' => $totalBiaya,
                'total_margin' => $totalMargin,
                'ppn' => $ppn,
                'grand_total_with_ppn' => $grandTotal,
            ]);

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            Log::info('BoQ import and penawaran creation completed successfully', [
                'user_id' => auth()->id(),
                'penawaran_id' => $penawaran->id,
                'no_penawaran' => $penawaran->no_penawaran,
                'items_count' => count($validated['items']),
                'grand_total' => $grandTotal,
                'execution_time_ms' => $executionTime
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Penawaran berhasil dibuat dengan status DRAFT',
                'data' => [
                    'penawaran_id' => $penawaran->id,
                    'no_penawaran' => $penawaran->no_penawaran,
                    'status' => $penawaran->status,
                    'ai_status' => $penawaran->ai_status,
                    'grand_total' => $grandTotal,
                ]
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('BoQ import and penawaran creation failed', [
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'execution_time_ms' => $executionTime
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error membuat penawaran: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Analyze manual penawaran before saving (no database entry yet)
     * Returns DSS analysis results for preview
     */
    public function analyzeManual(Request $request)
    {
        $startTime = microtime(true);
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'tanggal' => 'required|date',
            'wilayah' => 'nullable|string',
            'jenis_pekerjaan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_asli' => 'required|numeric|min:0',
            'items.*.persentase_margin' => 'required|numeric|min:0|max:100',
        ]);

        try {
            Log::info('Starting manual penawaran analysis', [
                'user_id' => auth()->id(),
                'client_id' => $validated['client_id'],
                'items_count' => count($validated['items']),
                'timestamp' => now()->toDateTimeString()
            ]);

            // Calculate totals
            $totalBiaya = 0;
            $totalMargin = 0;
            $itemCount = 0;

            foreach ($validated['items'] as $item) {
                $hargaAsli = (float)$item['harga_asli'];
                $persentaseMargin = (float)$item['persentase_margin'];
                $jumlah = (int)$item['jumlah'];

                $marginPerUnit = $hargaAsli * ($persentaseMargin / 100);
                $totalBiayaItem = $hargaAsli * $jumlah;
                $totalMarginItem = $marginPerUnit * $jumlah;

                $totalBiaya += $totalBiayaItem;
                $totalMargin += $totalMarginItem;
                $itemCount++;
            }

            $subtotal = $totalBiaya + $totalMargin;
            $ppn = $subtotal * 0.11;
            $grandTotal = $subtotal + $ppn;

            // Perform DSS analysis (simplified - no database save)
            $analysis = $this->performDSSAnalysis([
                'items_count' => $itemCount,
                'grand_total' => $grandTotal,
                'subtotal' => $subtotal,
                'client_id' => $validated['client_id'],
                'wilayah' => $validated['wilayah'] ?? 'Kota Semarang',
                'jenis_pekerjaan' => $validated['jenis_pekerjaan'] ?? 'Project / Purchase Order',
            ]);

            $executionTime = round((microtime(true) - $startTime) * 1000, 2); // ms

            Log::info('Manual penawaran analysis completed successfully', [
                'user_id' => auth()->id(),
                'risk_level' => $analysis['risk_level'],
                'grand_total' => $grandTotal,
                'execution_time_ms' => $executionTime
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Analisis berhasil',
                'risk_level' => $analysis['risk_level'],
                'recommendation' => $analysis['recommendation'],
                'predictions' => [
                    'lr' => $analysis['predictions']['linear_regression']['prediction'],
                    'rf' => $analysis['predictions']['random_forest']['prediction'],
                    'xgb' => $analysis['predictions']['xgboost']['prediction'],
                    'dl' => $analysis['predictions']['deep_learning']['prediction'],
                    'ma' => $analysis['predictions']['moving_average']['prediction'],
                ],
                'predictions_meta' => $analysis['predictions'],
                'data' => [
                    'grand_total' => $grandTotal,
                    'total_biaya' => $totalBiaya,
                    'total_margin' => $totalMargin,
                    'ppn' => $ppn,
                    'item_count' => $itemCount,
                ]
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Error analyzing manual penawaran', [
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'execution_time_ms' => $executionTime
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error menganalisis penawaran: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Perform DSS analysis without saving to database
     * Returns risk level, recommendations, and predictions
     */
    private function performDSSAnalysis(array $data): array
    {
        $itemCount = $data['items_count'] ?? 0;
        $grandTotal = $data['grand_total'] ?? 0;
        $jenisPekerjaan = $data['jenis_pekerjaan'] ?? 'Project / Purchase Order';
        $wilayah = $data['wilayah'] ?? 'Kota Semarang';
        
        // Define default predictions (fallbacks)
        $predictions = [
            'linear_regression' => ['prediction' => $grandTotal * 0.95, 'time_ms' => 0],
            'random_forest' => ['prediction' => $grandTotal * 0.92, 'time_ms' => 0],
            'xgboost' => ['prediction' => $grandTotal * 0.93, 'time_ms' => 0],
            'deep_learning' => ['prediction' => $grandTotal * 0.94, 'time_ms' => 0],
            'moving_average' => ['prediction' => $grandTotal * 0.9, 'time_ms' => 0],
        ];
        
        $success = false;
        
        try {
            $inputData = json_encode([
                'jenis_pekerjaan' => $jenisPekerjaan,
                'wilayah' => $wilayah,
                'grand_total' => (float)$grandTotal
            ]);
            $base64Input = base64_encode($inputData);
            $scriptPath = base_path('storage/app/ml/predict_all_models.py');
            $command = "python \"$scriptPath\" $base64Input 2>&1";
            
            $output = shell_exec($command);
            if ($output) {
                $result = json_decode($output, true);
                if ($result && isset($result['success']) && $result['success']) {
                    $predictions = $result['predictions'];
                    $success = true;
                } else {
                    \Log::warning('Python predict_all_models.py returned error: ' . ($result['error'] ?? 'Unknown error'));
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to run python predict_all_models.py: ' . $e->getMessage());
        }
        
        // Native PHP fallback if Python failed
        if (!$success) {
            try {
                $prediksiLR = \App\Services\DSSPredictionService::predict($jenisPekerjaan, $wilayah, $grandTotal);
                $predictions['linear_regression']['prediction'] = $prediksiLR;
            } catch (\Exception $e) {
                \Log::error('Native PHP fallback prediction failed: ' . $e->getMessage());
            }
        }
        
        $prediksiLR = $predictions['linear_regression']['prediction'];
        $estimatedOverrun = (($prediksiLR - $grandTotal) / $grandTotal) * 100;
        $marginStatus = $estimatedOverrun > 0 ? 'overrun' : 'aman';
        
        // 100% ML Driven Risk Assessment
        if ($estimatedOverrun > 10) {
            $riskLevel = 'Tinggi';
            $recommendation = '🔴 DANGER: Model AI memprediksi potensi kerugian/overrun sebesar ' . round($estimatedOverrun, 1) . '%. Prediksi aktual biaya adalah Rp ' . number_format($prediksiLR, 0, ',', '.') . ' berbanding penawaran Rp ' . number_format($grandTotal, 0, ',', '.') . '. Sangat disarankan untuk merevisi penawaran!';
        } elseif ($estimatedOverrun > 0) {
            $riskLevel = 'Sedang';
            $recommendation = '⚠️ WARNING: Model AI memprediksi kerugian/overrun sebesar ' . round($estimatedOverrun, 1) . '%. Ada potensi biaya membengkak melebihi penawaran. Review kembali harga material dan kuantitas.';
        } else {
            $riskLevel = 'Rendah';
            $recommendation = '✓ Risiko rendah. Estimasi biaya terlihat sangat aman, diprediksi ada penghematan/margin ekstra sebesar ' . abs(round($estimatedOverrun, 1)) . '%.';
        }
        
        return [
            'risk_level' => $riskLevel,
            'risk_score' => $estimatedOverrun,
            'complexity_score' => min(10, $itemCount * 0.5 + 1), // normalized score out of 10
            'prediksi_lr' => round($prediksiLR, 0),
            'margin_status' => $marginStatus,
            'estimated_overrun_percent' => round($estimatedOverrun, 2),
            'recommendation' => $recommendation,
            'predictions' => $predictions,
        ];
    }

    /**
     * Show form to create penawaran with BoQ
     * (Phase 2: UI for DSS workflow)
     */
    public function showCreateBoq()
    {
        $clients = Client::orderBy('nama')->get();
        
        return view('penawaran.create_boq', [
            'clients' => $clients
        ]);
    }

    /**
     * Copy items from previous penawaran to a new/existing penawaran
     * 
     * Price Strategy Options:
     * - 'keep': Keep original price from source penawaran
     * - 'latest': Use latest material price (from material master)
     * - 'average': Use average price from material history
     * - 'override': Use custom provided prices
     * 
     * Returns: Updated penawaran with recalculated totals
     */
    public function copyItemsFromPenawaran(CopyItemsFromPenawaranRequest $request)
    {
        $startTime = microtime(true);
        
        $validated = $request->validated();

        try {
            $sourcePenawaran = Penawaran::findOrFail($validated['source_penawaran_id']);
            $targetPenawaran = Penawaran::findOrFail($validated['target_penawaran_id']);
            $priceStrategy = $validated['price_strategy'];

            Log::info('Starting copy items from penawaran', [
                'user_id' => auth()->id(),
                'source_penawaran_id' => $sourcePenawaran->id,
                'target_penawaran_id' => $targetPenawaran->id,
                'price_strategy' => $priceStrategy,
                'source_items_count' => $sourcePenawaran->items()->count()
            ]);

            // Get source items
            $sourceItems = $sourcePenawaran->items()->get();

            if ($sourceItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penawaran sumber tidak memiliki item',
                ], 422);
            }

            // Build override price map for quick lookup
            $overridePrices = collect($validated['override_prices'] ?? [])->keyBy('item_id');

            // Clear existing target items (optional - can be changed to append)
            $targetPenawaran->items()->delete();

            $totalBiaya = 0;
            $totalMargin = 0;
            $copiedItems = [];

            foreach ($sourceItems as $sourceItem) {
                // Determine harga_asli based on strategy
                $hargaAsli = $sourceItem->harga_asli;

                if ($overridePrices->has($sourceItem->id)) {
                    // Override strategy
                    $override = $overridePrices->get($sourceItem->id);
                    $hargaAsli = $override['harga_asli'];
                    $persentaseMargin = $override['persentase_margin'];
                } elseif ($priceStrategy === 'latest') {
                    // Use latest material price if available
                    if ($sourceItem->material_id) {
                        $material = Material::find($sourceItem->material_id);
                        if ($material && $material->harga) {
                            $hargaAsli = $material->harga;
                        }
                    }
                    $persentaseMargin = $sourceItem->persentase_margin;
                } elseif ($priceStrategy === 'average') {
                    // Use average price from item history
                    if ($sourceItem->material_id) {
                        $avgPrice = ItemPenawaran::where('material_id', $sourceItem->material_id)
                            ->whereHas('penawaran', fn($q) => $q->where('status', 'disetujui'))
                            ->avg('harga_asli');
                        
                        if ($avgPrice) {
                            $hargaAsli = $avgPrice;
                        }
                    }
                    $persentaseMargin = $sourceItem->persentase_margin;
                } else {
                    // 'keep' strategy - use original price
                    $persentaseMargin = $sourceItem->persentase_margin;
                }

                // Calculate harga_jual
                $marginPerUnit = $hargaAsli * ($persentaseMargin / 100);
                $hargaJual = $hargaAsli + $marginPerUnit;

                // Calculate totals
                $totalBiayaItem = $hargaAsli * $sourceItem->jumlah;
                $totalMarginItem = $marginPerUnit * $sourceItem->jumlah;

                $totalBiaya += $totalBiayaItem;
                $totalMargin += $totalMarginItem;

                // Create new item in target penawaran
                $newItem = ItemPenawaran::create([
                    'penawaran_id' => $targetPenawaran->id,
                    'material_id' => $sourceItem->material_id,
                    'nama' => $sourceItem->nama,
                    'satuan' => $sourceItem->satuan,
                    'jumlah' => $sourceItem->jumlah,
                    'harga_asli' => $hargaAsli,
                    'persentase_margin' => $persentaseMargin,
                    'harga_jual' => $hargaJual,
                ]);

                $copiedItems[] = [
                    'id' => $newItem->id,
                    'nama' => $newItem->nama,
                    'jumlah' => $newItem->jumlah,
                    'harga_asli' => $hargaAsli,
                    'harga_jual' => $hargaJual,
                    'strategy_used' => $priceStrategy,
                ];
            }

            // Calculate PPN & grand total
            $subtotal = $totalBiaya + $totalMargin;
            $ppn = $subtotal * 0.11;
            $grandTotal = $subtotal + $ppn;

            // Update target penawaran totals
            $targetPenawaran->update([
                'total_biaya' => $totalBiaya,
                'total_margin' => $totalMargin,
                'ppn' => $ppn,
                'grand_total_with_ppn' => $grandTotal,
            ]);

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            Log::info('Copy items from penawaran completed successfully', [
                'user_id' => auth()->id(),
                'source_penawaran_id' => $sourcePenawaran->id,
                'target_penawaran_id' => $targetPenawaran->id,
                'items_copied' => count($copiedItems),
                'price_strategy' => $priceStrategy,
                'grand_total' => $grandTotal,
                'execution_time_ms' => $executionTime
            ]);

            return response()->json([
                'success' => true,
                'message' => count($copiedItems) . ' item berhasil disalin dengan strategi ' . $priceStrategy,
                'data' => [
                    'target_penawaran_id' => $targetPenawaran->id,
                    'items_copied' => $copiedItems,
                    'totals' => [
                        'total_biaya' => $totalBiaya,
                        'total_margin' => $totalMargin,
                        'ppn' => $ppn,
                        'grand_total_with_ppn' => $grandTotal,
                    ],
                    'price_strategy' => $priceStrategy,
                ]
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Error copying items from penawaran', [
                'user_id' => auth()->id(),
                'source_penawaran_id' => $validated['source_penawaran_id'] ?? null,
                'target_penawaran_id' => $validated['target_penawaran_id'] ?? null,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'execution_time_ms' => $executionTime
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error menyalin item: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get price trend for a material from historical penawaran data
     * 
     * Returns: Historical prices, average, min/max, trend direction
     * Used for: Price recommendations and visualization
     */
    public function getItemPriceTrend(GetItemPriceTrendRequest $request)
    {
        $validated = $request->validated();

        try {
            $material = Material::findOrFail($validated['material_id']);
            $limit = $validated['limit'] ?? 10;

            Log::info('Fetching price trend for material', [
                'user_id' => auth()->id(),
                'material_id' => $material->id,
                'material_kode' => $material->kode,
                'limit' => $limit
            ]);

            // Get historical prices from approved penawaran items
            $priceHistory = ItemPenawaran::where('material_id', $material->id)
                ->whereHas('penawaran', fn($q) => $q->where('status', 'disetujui'))
                ->with(['penawaran' => fn($q) => $q->select('id', 'no_penawaran')])
                ->orderBy('created_at', 'DESC')
                ->limit($limit)
                ->get(['harga_asli', 'persentase_margin', 'harga_jual', 'jumlah', 'created_at', 'penawaran_id'])
                ->map(fn($item) => [
                    'harga_asli' => $item->harga_asli,
                    'persentase_margin' => $item->persentase_margin,
                    'harga_jual' => $item->harga_jual,
                    'jumlah' => $item->jumlah,
                    'penawaran_no' => $item->penawaran ? $item->penawaran->no_penawaran : '-',
                    'date' => $item->created_at->format('Y-m-d'),
                ]);

            if ($priceHistory->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Belum ada riwayat harga untuk material ini',
                    'data' => [
                        'material_id' => $material->id,
                        'material_kode' => $material->kode,
                        'material_nama' => $material->nama,
                        'history' => [],
                        'stats' => null,
                    ]
                ]);
            }

            // Calculate statistics from selling prices (with margin)
            $allPrices = $priceHistory->pluck('harga_jual');
            $avgPrice = $allPrices->avg();
            $minPrice = $allPrices->min();
            $maxPrice = $allPrices->max();
            $latestPrice = $allPrices->first();

            // Calculate trend (comparing latest with average)
            $priceChange = (($latestPrice - $avgPrice) / $avgPrice) * 100;
            $trendDirection = $priceChange > 2 ? 'increasing' : ($priceChange < -2 ? 'decreasing' : 'stable');

            // Calculate average margins
            $avgMargin = $priceHistory->pluck('persentase_margin')->avg();

            Log::info('Price trend analysis completed', [
                'user_id' => auth()->id(),
                'material_id' => $material->id,
                'records_count' => $priceHistory->count(),
                'trend' => $trendDirection,
                'price_change_percent' => round($priceChange, 2)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Riwayat harga berhasil diambil',
                'data' => [
                    'material_id' => $material->id,
                    'material_kode' => $material->kode,
                    'material_nama' => $material->nama,
                    'current_price' => $material->harga ?? null,
                    'history' => $priceHistory,
                    'stats' => [
                        'avg_price' => round($avgPrice, 2),
                        'min_price' => round($minPrice, 2),
                        'max_price' => round($maxPrice, 2),
                        'latest_price' => round($latestPrice, 2),
                        'avg_margin' => round($avgMargin, 2),
                        'price_change_percent' => round($priceChange, 2),
                        'trend' => $trendDirection,
                        'records_count' => $priceHistory->count(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching price trend', [
                'user_id' => auth()->id(),
                'material_id' => $validated['material_id'] ?? null,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error mengambil riwayat harga: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Find similar penawaran for a given client to reuse items from
     * 
     * Similarity based on:
     * - Same client
     * - Same project type (inferred from item types)
     * - Status = disetujui (approved/completed)
     * 
     * Returns: List of previous penawaran sorted by recency
     */
    public function findSimilarPenawaran(FindSimilarPenawaranRequest $request)
    {
        $validated = $request->validated();

        try {
            $clientId = $validated['client_id'];
            $limit = $validated['limit'] ?? 5;
            $excludeId = $validated['exclude_penawaran_id'] ?? null;

            Log::info('Finding similar penawaran', [
                'user_id' => auth()->id(),
                'client_id' => $clientId,
                'limit' => $limit,
                'exclude_penawaran_id' => $excludeId
            ]);

            // Get approved penawaran for this client, excluding current one
            $query = Penawaran::where('client_id', $clientId)
                ->where('status', 'disetujui')
                ->orderBy('created_at', 'DESC')
                ->limit($limit);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            $similarPenawaran = $query->get();

            if ($similarPenawaran->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Belum ada penawaran sebelumnya untuk client ini',
                    'data' => [
                        'client_id' => $clientId,
                        'penawaran' => [],
                    ]
                ]);
            }

            // Map to response format with items
            $penawaranData = $similarPenawaran->map(fn($p) => [
                'id' => $p->id,
                'no_penawaran' => $p->no_penawaran,
                'tanggal' => $p->tanggal->format('Y-m-d'),
                'status' => $p->status,
                'items_count' => $p->items()->count(),
                'items' => $p->items->map(fn($item) => [
                    'id' => $item->id,
                    'material_id' => $item->material_id,
                    'material_nama' => $item->material?->nama ?? 'Unknown',
                    'nama_satuan' => $item->satuan ?? $item->material?->satuan ?? '-',
                    'jumlah' => $item->jumlah,
                    'harga_asli' => $item->harga_asli,
                    'harga_jual' => $item->harga_jual,
                    'margin' => $item->persentase_margin ?? 0,
                ]),
                'grand_total_with_ppn' => $p->grand_total_with_ppn,
                'total_biaya' => $p->total_biaya,
                'total_margin' => $p->total_margin,
                'user_note' => $p->ai_notes ?? null,
            ]);

            Log::info('Similar penawaran found', [
                'user_id' => auth()->id(),
                'client_id' => $clientId,
                'penawaran_count' => $similarPenawaran->count()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ditemukan ' . $similarPenawaran->count() . ' penawaran sebelumnya',
                'data' => [
                    'client_id' => $clientId,
                    'penawaran' => $penawaranData,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error finding similar penawaran', [
                'user_id' => auth()->id(),
                'client_id' => $validated['client_id'] ?? null,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error mencari penawaran sebelumnya: ' . $e->getMessage(),
            ], 500);
        }
    }
}
