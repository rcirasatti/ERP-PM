@extends('layouts.app')

@section('title', 'Buat Penawaran dari BoQ')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Buat Penawaran Baru (BoQ)</h1>
        <p class="text-gray-600 mt-2">Upload file Excel dengan rincian material dan harga untuk membuat penawaran</p>
    </div>

    <!-- Step Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-white font-bold">1</div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Upload BoQ</p>
                        <p class="text-xs text-gray-500">Unggah file Excel</p>
                    </div>
                </div>
            </div>
            <div class="flex-1 mx-4 h-0.5 bg-gray-300"></div>
            <div class="flex-1">
                <div class="flex items-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-300 text-gray-500 font-bold">2</div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Preview & AI</p>
                        <p class="text-xs text-gray-500">Analisis data</p>
                    </div>
                </div>
            </div>
            <div class="flex-1 mx-4 h-0.5 bg-gray-300"></div>
            <div class="flex-1">
                <div class="flex items-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-300 text-gray-500 font-bold">3</div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Finish</p>
                        <p class="text-xs text-gray-500">Simpan penawaran</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Form Section -->
        <div class="lg:col-span-2">
            <!-- Client Selection -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penawaran</h2>
                
                <form id="boqForm" class="space-y-6">
                    @csrf
                    
                    <!-- Client Selection -->
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Klien <span class="text-red-500">*</span>
                        </label>
                        <select id="client_id" name="client_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Pilih Klien --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Penawaran Date -->
                    <div>
                        <label for="tanggal_penawaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Penawaran <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_penawaran" name="tanggal_penawaran" required 
                               value="{{ date('Y-m-d') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- File Upload Area -->
                    <div>
                        <label for="boq_file" class="block text-sm font-medium text-gray-700 mb-2">
                            File BoQ (Excel) <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- File Drag-Drop Zone -->
                        <div id="dropZone" class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-colors"
                             ondragover="event.preventDefault(); event.currentTarget.classList.add('border-blue-500', 'bg-blue-50');"
                             ondragleave="event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');"
                             ondrop="handleFileDrop(event)">
                            
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-2-10l-8.5-8.5a4 4 0 00-5.66 0L8 20m20-4v12m-6-6h12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            
                            <p class="text-gray-600 font-medium mb-1">Seret file atau klik untuk memilih</p>
                            <p class="text-sm text-gray-500">File Excel (.xlsx, .xls) dengan format BoQ standar</p>
                            
                            <input type="file" id="boq_file" name="boq_file" accept=".xlsx,.xls" required 
                                   class="hidden" onchange="handleFileSelect(event)">
                        </div>
                        
                        <!-- Selected File Display -->
                        <div id="selectedFile" class="mt-3 hidden">
                            <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded p-3">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                    </svg>
                                    <span id="fileName" class="text-sm text-gray-900"></span>
                                </div>
                                <button type="button" onclick="clearFile()" class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button type="button" id="previewBtn" onclick="handlePreview()" 
                                class="flex-1 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            <span id="previewBtnText">Lihat Preview</span>
                            <span id="previewSpinner" class="hidden ml-2 inline-block animate-spin">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                            </span>
                        </button>
                        <a href="{{ route('penawaran.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            <!-- Template Download Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-2a1 1 0 100 2 1 1 0 000-2zM8 7a1 1 0 100 2 1 1 0 000-2zm1 5a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-900">Butuh template?</h3>
                        <p class="text-sm text-blue-700 mt-1">
                            <a href="{{ route('penawaran.boq-template') }}" class="underline font-medium hover:text-blue-900">Download template Excel</a> untuk format yang benar
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Summary Section -->
        <div class="lg:col-span-1">
            <!-- Summary Card -->
            <div class="bg-white rounded-lg shadow p-6 sticky top-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan</h3>
                
                <div class="space-y-4">
                    <!-- Item Count -->
                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600">Total Item</span>
                        <span id="itemCount" class="text-2xl font-bold text-gray-900">0</span>
                    </div>

                    <!-- Grand Total -->
                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600">Subtotal</span>
                        <span id="subtotal" class="text-lg font-semibold text-gray-900">Rp 0</span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600">PPN (11%)</span>
                        <span id="ppnAmount" class="text-lg font-semibold text-gray-900">Rp 0</span>
                    </div>

                    <div class="flex justify-between items-center py-3 bg-blue-50 px-3 rounded">
                        <span class="text-gray-900 font-semibold">Grand Total</span>
                        <span id="grandTotal" class="text-2xl font-bold text-blue-600">Rp 0</span>
                    </div>

                    <!-- Error Count -->
                    <div id="errorSection" class="hidden">
                        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded">
                            <p class="text-sm text-red-700">
                                <span id="errorCount">0</span> error ditemukan saat parsing
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Table Section (Hidden initially) -->
    <div id="previewSection" class="hidden mt-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Preview Data BoQ</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama Material</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Satuan</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Jumlah</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Harga Satuan</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Margin %</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody id="previewTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Filled by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Proceed to Analysis Button -->
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" onclick="resetPreview()" 
                    class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Upload Ulang
            </button>
            <button type="button" id="analyzeBtn" onclick="analyzeWithDSS()" 
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Lanjut ke Analisis AI
            </button>
        </div>
    </div>

    <!-- DSS Analysis Results Section (Hidden initially) -->
    <div id="analysisSection" class="hidden mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Risk Assessment Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Penilaian Risiko</h3>
                
                <div class="space-y-4">
                    <!-- Risk Level Badge -->
                    <div>
                        <label class="text-sm font-medium text-gray-600 mb-2 block">Tingkat Risiko</label>
                        <div id="riskBadge" class="inline-block px-4 py-2 rounded-full text-white font-semibold">
                            <!-- Filled by JS -->
                        </div>
                    </div>

                    <!-- Risk Factors -->
                    <div class="py-3 border-t border-gray-200">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-2">Faktor Risiko</p>
                        <div id="riskFactors" class="text-sm text-gray-700 space-y-1">
                            <!-- Filled by JS -->
                        </div>
                    </div>

                    <!-- Complexity Score -->
                    <div class="py-3 border-t border-gray-200">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-600">Kompleksitas Item</span>
                            <span id="complexityScore" class="text-sm font-semibold text-gray-900">0/10</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="complexityBar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Historical Variance -->
                    <div class="py-3 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Overrun Historis</span>
                            <span id="historyOverrun" class="text-sm font-semibold text-gray-900">0%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Predictions Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Prediksi AI</h3>
                
                <div class="space-y-6">
                    <!-- Linear Regression Prediction -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Prediksi Biaya Aktual (Linear)</p>
                        <p id="lrPrediction" class="text-2xl font-bold text-gray-900 mb-2">Rp 0</p>
                        <div class="flex items-center text-sm">
                            <span id="lrVariance" class="text-green-600 font-semibold">0%</span>
                            <span class="text-gray-600 ml-1">(dari estimasi)</span>
                        </div>
                    </div>

                    <!-- Moving Average Prediction -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Prediksi Biaya Aktual (Moving Avg)</p>
                        <p id="maPrediction" class="text-2xl font-bold text-gray-900 mb-2">Rp 0</p>
                        <div class="flex items-center text-sm">
                            <span id="maVariance" class="text-green-600 font-semibold">0%</span>
                            <span class="text-gray-600 ml-1">(dari estimasi)</span>
                        </div>
                    </div>

                    <!-- AI Recommendation -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded">
                        <p class="text-sm font-medium text-blue-900 mb-2">Rekomendasi AI</p>
                        <p id="recommendation" class="text-sm text-blue-800">
                            <!-- Filled by JS -->
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decision Buttons -->
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Keputusan Anda</h3>
            <p class="text-gray-600 mb-4 text-sm">Berdasarkan analisis DSS di atas, pilih tindakan yang sesuai:</p>
            
            <div class="flex gap-3">
                <button type="button" id="approveBtn" onclick="submitDecision('approve')" 
                        class="flex-1 px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <span class="inline-block mr-2">✓</span>Setujui Penawaran
                </button>
                <button type="button" id="reviseBtn" onclick="submitDecision('revise')" 
                        class="flex-1 px-6 py-3 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                    <span class="inline-block mr-2">↻</span>Revisi
                </button>
                <button type="button" id="rejectBtn" onclick="submitDecision('reject')" 
                        class="flex-1 px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <span class="inline-block mr-2">✗</span>Tolak
                </button>
                <a href="{{ route('penawaran.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Loading & Error States -->
    <div id="loadingState" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg p-8 text-center">
            <svg class="h-12 w-12 text-blue-600 animate-spin mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0c4.418 0 8 3.582 8 8h-2zm15.535-8.172a.5.5 0 00-.707.707L19.586 6l-1.414-1.414a.5.5 0 00-.707.707L18.879 6.707 17.465 8.12a.5.5 0 00.707.707L19.586 7.414l1.414 1.414a.5.5 0 00.707-.707L20.293 7.414l1.414-1.414a.5.5 0 000-.707z"/>
            </svg>
            <p class="text-gray-900 font-medium" id="loadingText">Memproses...</p>
        </div>
    </div>

    <div id="errorState" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg p-8 max-w-md">
            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mx-auto mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Terjadi Kesalahan</h3>
            <p id="errorMessage" class="text-gray-600 text-center text-sm mb-6">
                <!-- Error message filled by JS -->
            </p>
            <button onclick="closeError()" class="w-full px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Global state
let uploadedFile = null;
let previewData = null;
let analysisData = null;
let penawaranId = null;

// ============================================
// FILE HANDLING
// ============================================

function handleFileDrop(event) {
    event.preventDefault();
    event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');
    
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        uploadedFile = files[0];
        displaySelectedFile();
    }
}

function handleFileSelect(event) {
    uploadedFile = event.target.files[0];
    displaySelectedFile();
}

function displaySelectedFile() {
    if (uploadedFile) {
        document.getElementById('fileName').textContent = uploadedFile.name;
        document.getElementById('selectedFile').classList.remove('hidden');
        document.getElementById('previewBtn').disabled = false;
    }
}

function clearFile() {
    uploadedFile = null;
    document.getElementById('boq_file').value = '';
    document.getElementById('selectedFile').classList.add('hidden');
    document.getElementById('previewBtn').disabled = true;
}

// ============================================
// PREVIEW HANDLING
// ============================================

async function handlePreview() {
    if (!uploadedFile || !document.getElementById('client_id').value) {
        showError('Pilih klien terlebih dahulu');
        return;
    }

    const formData = new FormData();
    formData.append('boq_file', uploadedFile);
    formData.append('client_id', document.getElementById('client_id').value);
    formData.append('tanggal_penawaran', document.getElementById('tanggal_penawaran').value);

    showLoading('Mengekstrak data BoQ...');

    try {
        const response = await fetch('{{ route("penawaran.boq-preview") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Gagal mengekstrak preview');
        }

        previewData = data.data;
        displayPreview(data.data);
        document.getElementById('previewSection').classList.remove('hidden');
        window.scrollTo({ top: document.getElementById('previewSection').offsetTop - 100, behavior: 'smooth' });

    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

function displayPreview(data) {
    const tbody = document.getElementById('previewTableBody');
    tbody.innerHTML = '';

    let totalBiaya = 0;

    data.items.forEach(item => {
        const row = document.createElement('tr');
        const rowTotal = item.total_biaya_item;
        totalBiaya += rowTotal;

        row.innerHTML = `
            <td class="px-4 py-3 text-sm text-gray-900">${item.kode}</td>
            <td class="px-4 py-3 text-sm text-gray-900">${item.nama}</td>
            <td class="px-4 py-3 text-sm text-gray-900">${item.satuan}</td>
            <td class="px-4 py-3 text-sm text-right text-gray-900">${item.jumlah.toLocaleString('id-ID')}</td>
            <td class="px-4 py-3 text-sm text-right text-gray-900">Rp ${item.harga_asli.toLocaleString('id-ID')}</td>
            <td class="px-4 py-3 text-sm text-right text-gray-900">${item.persentase_margin}%</td>
            <td class="px-4 py-3 text-sm text-right text-gray-900 font-medium">Rp ${rowTotal.toLocaleString('id-ID')}</td>
        `;
        tbody.appendChild(row);
    });

    // Update summary
    document.getElementById('itemCount').textContent = data.items.length;
    document.getElementById('subtotal').textContent = 'Rp ' + data.subtotal.toLocaleString('id-ID');
    document.getElementById('ppnAmount').textContent = 'Rp ' + data.ppn_11_percent.toLocaleString('id-ID');
    document.getElementById('grandTotal').textContent = 'Rp ' + data.grand_total.toLocaleString('id-ID');

    if (data.error_count > 0) {
        document.getElementById('errorSection').classList.remove('hidden');
        document.getElementById('errorCount').textContent = data.error_count;
    } else {
        document.getElementById('errorSection').classList.add('hidden');
    }
}

function resetPreview() {
    previewData = null;
    analysisData = null;
    penawaranId = null;
    document.getElementById('previewSection').classList.add('hidden');
    document.getElementById('analysisSection').classList.add('hidden');
    clearFile();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ============================================
// DSS ANALYSIS
// ============================================

async function analyzeWithDSS() {
    if (!previewData) {
        showError('Data preview tidak tersedia');
        return;
    }

    showLoading('Menganalisis dengan AI DSS...');

    try {
        // First, save penawaran as draft
        const draftResponse = await fetch('{{ route("penawaran.boq-store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                client_id: document.getElementById('client_id').value,
                tanggal_penawaran: document.getElementById('tanggal_penawaran').value,
                items: previewData.items,
                grand_total: previewData.grand_total
            })
        });

        const draftData = await draftResponse.json();

        if (!draftResponse.ok) {
            throw new Error(draftData.message || 'Gagal menyimpan draft penawaran');
        }

        penawaranId = draftData.data.id;

        // Now analyze with DSS
        const analysisResponse = await fetch('{{ url("/api/dss/analyze") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                penawaran_id: penawaranId,
                grand_total: previewData.grand_total
            })
        });

        analysisData = await analysisResponse.json();

        if (!analysisResponse.ok) {
            throw new Error(analysisData.message || 'Gagal menganalisis dengan DSS');
        }

        displayAnalysisResults(analysisData.data);
        document.getElementById('analysisSection').classList.remove('hidden');
        window.scrollTo({ top: document.getElementById('analysisSection').offsetTop - 100, behavior: 'smooth' });

    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

function displayAnalysisResults(data) {
    // Risk badge
    const riskBadge = document.getElementById('riskBadge');
    const riskClass = {
        'Rendah': 'bg-green-600',
        'Sedang': 'bg-yellow-600',
        'Tinggi': 'bg-red-600'
    };
    riskBadge.className = `inline-block px-4 py-2 rounded-full text-white font-semibold ${riskClass[data.risk_level] || 'bg-gray-600'}`;
    riskBadge.textContent = data.risk_level;

    // Risk factors
    const riskFactorsHtml = `
        <div>• Jumlah Item: ${data.item_count}</div>
        <div>• Kompleksitas: ${data.complexity_score.toFixed(1)}/10</div>
        <div>• Overrun Historis: ${data.historical_overrun_rate.toFixed(1)}%</div>
    `;
    document.getElementById('riskFactors').innerHTML = riskFactorsHtml;

    // Complexity bar
    const complexityPercent = (data.complexity_score / 10) * 100;
    document.getElementById('complexityScore').textContent = data.complexity_score.toFixed(1) + '/10';
    document.getElementById('complexityBar').style.width = complexityPercent + '%';

    // History overrun
    document.getElementById('historyOverrun').textContent = data.historical_overrun_rate.toFixed(1) + '%';

    // Predictions
    document.getElementById('lrPrediction').textContent = 'Rp ' + Math.round(data.predictions.linear_regression).toLocaleString('id-ID');
    const lrVariance = ((data.predictions.linear_regression - data.grand_total) / data.grand_total * 100).toFixed(1);
    document.getElementById('lrVariance').textContent = lrVariance + '%';
    document.getElementById('lrVariance').className = 'font-semibold ' + (lrVariance > 0 ? 'text-red-600' : 'text-green-600');

    document.getElementById('maPrediction').textContent = 'Rp ' + Math.round(data.predictions.moving_average).toLocaleString('id-ID');
    const maVariance = ((data.predictions.moving_average - data.grand_total) / data.grand_total * 100).toFixed(1);
    document.getElementById('maVariance').textContent = maVariance + '%';
    document.getElementById('maVariance').className = 'font-semibold ' + (maVariance > 0 ? 'text-red-600' : 'text-green-600');

    // Recommendation
    let recommendation = '';
    if (data.risk_level === 'Tinggi') {
        recommendation = `⚠️ Risiko tinggi terdeteksi. Pertimbangkan untuk merevisi keseluruhan proposal atau menambah margin keamanan.`;
    } else if (data.risk_level === 'Sedang') {
        recommendation = `⚡ Risiko sedang. Prediksi menunjukkan kemungkinan overrun ${Math.abs(Math.round(lrVariance))}%. Sebaiknya review kembali estimasi biaya.`;
    } else {
        recommendation = `✓ Risiko rendah. Penawaran ini memiliki margin keamanan yang baik untuk dikontrak.`;
    }
    document.getElementById('recommendation').textContent = recommendation;
}

// ============================================
// DECISION SUBMISSION
// ============================================

async function submitDecision(decision) {
    if (!penawaranId || !analysisData) {
        showError('Data analisis tidak lengkap');
        return;
    }

    showLoading(`Mengirim keputusan: ${decision}...`);

    try {
        const response = await fetch('{{ url("/api/dss/approve") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                penawaran_id: penawaranId,
                decision: decision,
                notes: ''
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Gagal menyimpan keputusan');
        }

        // Success - redirect or show confirmation
        showSuccess('Keputusan berhasil disimpan. Mengarahkan ke penawaran...');
        setTimeout(() => {
            window.location.href = '{{ route("penawaran.index") }}';
        }, 2000);

    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

// ============================================
// UI UTILITIES
// ============================================

function showLoading(message = 'Memproses...') {
    document.getElementById('loadingText').textContent = message;
    document.getElementById('loadingState').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
}

function showError(message) {
    document.getElementById('errorMessage').textContent = message;
    document.getElementById('errorState').classList.remove('hidden');
}

function closeError() {
    document.getElementById('errorState').classList.add('hidden');
}

function showSuccess(message) {
    // You could use a toast library here instead
    alert(message);
}

// Enable drag-drop on dropZone
document.getElementById('dropZone')?.addEventListener('click', () => {
    document.getElementById('boq_file').click();
});

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    // Set today's date as default
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal_penawaran').value = today;
});
</script>
@endpush

@endsection
