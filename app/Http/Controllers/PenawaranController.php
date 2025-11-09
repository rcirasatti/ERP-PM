<?php

namespace App\Http\Controllers;

use App\Models\Penawaran;
use App\Models\ItemPenawaran;
use App\Models\Client;
use App\Models\Material;
use App\Models\Inventory;
use App\Models\LogInventory;
use Illuminate\Http\Request;

class PenawaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penawaran = Penawaran::with('client')->orderBy('created_at', 'DESC')->get();
        
        // Calculate KPI data
        $totalPenawaran = $penawaran->count();
        $totalValue = $penawaran->sum('total_biaya');
        $pendingPenawaran = $penawaran->where('status', 'draft')->count();

        return view('quotations.index', compact('penawaran', 'totalPenawaran', 'totalValue', 'pendingPenawaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $materials = Material::with('inventory')->get();
        $noPenawaran = Penawaran::generateNoPenawaran();
        
        return view('quotations.create', compact('clients', 'materials', 'noPenawaran'));
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

            $totalBiaya += $totalHargaJual;
            $totalMargin += $marginValue;
        }

        // Update totals
        $penawaran->update([
            'total_biaya' => $totalBiaya,
            'total_margin' => $totalMargin,
        ]);

        return redirect()->route('quotations.show', $penawaran->id)->with('success', 'Penawaran berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penawaran $penawaran)
    {
        $penawaran->load('client', 'items.material');
        return view('quotations.show', compact('penawaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penawaran $penawaran)
    {
        $penawaran->load('client', 'items');
        $clients = Client::all();
        $materials = Material::with('inventory')->get();
        
        return view('quotations.edit', compact('penawaran', 'clients', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penawaran $penawaran)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,disetujui,ditolak,dibatalkan',
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

            $totalBiaya += $totalHargaJual;
            $totalMargin += $marginValue;
        }

        // Update penawaran
        $penawaran->update([
            'client_id' => $validated['client_id'],
            'tanggal' => $validated['tanggal'],
            'status' => $validated['status'],
            'total_biaya' => $totalBiaya,
            'total_margin' => $totalMargin,
        ]);

        return redirect()->route('quotations.show', $penawaran->id)->with('success', 'Penawaran berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penawaran $penawaran)
    {
        $penawaran->delete();
        return redirect()->route('quotations.index')->with('success', 'Penawaran berhasil dihapus');
    }

    /**
     * Update status penawaran
     */
    public function updateStatus(Request $request, Penawaran $penawaran)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,disetujui,ditolak,dibatalkan',
        ]);

        $oldStatus = $penawaran->status;
        $newStatus = $validated['status'];

        // Jika status berubah dari draft ke disetujui, kurangi stok inventory
        if ($oldStatus !== 'disetujui' && $newStatus === 'disetujui') {
            $this->reduceInventory($penawaran);
        }

        // Jika status berubah dari disetujui ke status lain, tambah kembali stok
        if ($oldStatus === 'disetujui' && $newStatus !== 'disetujui') {
            $this->restoreInventory($penawaran);
        }

        $penawaran->update([
            'status' => $newStatus,
        ]);

        return redirect()->route('quotations.show', $penawaran->id)->with('success', 'Status penawaran berhasil diubah menjadi ' . $penawaran->getStatusLabel());
    }

    /**
     * Reduce inventory when penawaran is approved
     */
    private function reduceInventory(Penawaran $penawaran)
    {
        $penawaran->load('items');

        foreach ($penawaran->items as $item) {
            $inventory = Inventory::where('material_id', $item->material_id)->first();

            if ($inventory) {
                $inventory->stok -= $item->jumlah;
                $inventory->save();

                // Log the inventory change
                LogInventory::create([
                    'material_id' => $item->material_id,
                    'jenis' => 'keluar',
                    'jumlah' => $item->jumlah,
                    'tanggal' => now(),
                    'catatan' => 'Pengurangan stok dari penawaran #' . $penawaran->no_penawaran . ' - ' . $penawaran->client->nama,
                    'dibuat_oleh' => auth()->user()->id,
                ]);
            }
        }
    }

    /**
     * Restore inventory when penawaran status is reverted from approved
     */
    private function restoreInventory(Penawaran $penawaran)
    {
        $penawaran->load('items');

        foreach ($penawaran->items as $item) {
            $inventory = Inventory::where('material_id', $item->material_id)->first();

            if ($inventory) {
                $inventory->stok += $item->jumlah;
                $inventory->save();

                // Log the inventory restore
                LogInventory::create([
                    'material_id' => $item->material_id,
                    'jenis' => 'masuk',
                    'jumlah' => $item->jumlah,
                    'tanggal' => now(),
                    'catatan' => 'Pemulihan stok dari penawaran #' . $penawaran->no_penawaran . ' (status dibatalkan)',
                    'dibuat_oleh' => auth()->user()->id,
                ]);
            }
        }
    }
}
