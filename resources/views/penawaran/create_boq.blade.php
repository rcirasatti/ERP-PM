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

    <!-- Info: Alternative Manual Method -->
    <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start">
        <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 100 2 1 1 0 000-2zM8 7a1 1 0 100 2 1 1 0 000-2zm1 5a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
        </svg>
        <div>
            <h3 class="font-medium text-blue-900 mb-1">📋 Butuh Template Excel?</h3>
            <p class="text-sm text-blue-800 mb-2">Belum punya file Excel? Download template BoQ standar kami dan isi dengan data material Anda.</p>
            <a href="{{ route('penawaran.boq-template') }}" class="text-sm font-medium text-blue-700 hover:text-blue-900 underline">
                Download template Excel →
            </a>
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
                        <select id="client_id" name="client_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent searchable-select">
                            <option value="">-- Pilih Klien --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->nama }}</option>
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
            <div class="bg-white rounded-2xl shadow-lg border border-purple-100 overflow-hidden flex flex-col">
                <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-5 text-white flex items-center justify-between">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        Prediksi Pengeluaran AI
                    </h3>
                    <div class="group relative">
                        <svg class="w-5 h-5 text-white/70 cursor-help hover:text-white transition" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="hidden group-hover:block absolute right-0 top-8 w-64 bg-gray-900 text-white text-sm rounded-lg p-3 z-10 shadow-xl border border-gray-700">
                            <p class="font-semibold mb-2">Tentang Prediksi AI:</p>
                            <p class="mb-2 text-gray-300">Nilai di bawah menunjukkan estimasi actual cost berdasarkan data historis. Jika lebih tinggi dari total penawaran, ada risiko overrun biaya.</p>
                            <p class="text-[10px] text-gray-400">Prediksi berdasarkan model Machine Learning yang telah ditraining dengan data historis proyek.</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-5 flex-1 flex flex-col gap-4 bg-purple-50/30">
                    <!-- Machine Learning Prediction -->
                    <div class="p-5 rounded-xl border border-purple-200 bg-white shadow-sm flex flex-col justify-center relative overflow-hidden">
                        <span class="text-xs font-medium text-purple-800 mb-1 uppercase tracking-wide">Estimasi Biaya Aktual</span>
                        <span id="lrPrediction" class="text-3xl font-black text-purple-900 relative z-10">Rp 0</span>
                        <svg class="absolute -bottom-6 -right-6 w-32 h-32 text-purple-100 opacity-60 z-0 transform -rotate-12" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                    </div>

                    <!-- Variance Indicator -->
                    <div id="varianceContainer" class="flex items-center p-4 rounded-xl border shadow-sm bg-white">
                        <div class="flex-1">
                            <p id="varianceLabel" class="text-xs font-bold text-gray-600 mb-1">Analisis Selisih</p>
                            <div class="flex items-end gap-2 mt-1">
                                <span id="lrVarianceNominal" class="text-xl font-bold text-gray-900">Rp 0</span>
                                <span id="lrVariance" class="text-xs font-bold px-2 py-0.5 rounded-full mb-0.5 bg-gray-100 text-gray-700">0%</span>
                            </div>
                        </div>
                        <div id="varianceIcon" class="flex-shrink-0 bg-gray-50 p-2.5 rounded-full shadow-sm border border-gray-100">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Multi-Model Comparison Container -->
                    <div id="multiModelComparison" class="mt-4 pt-4 border-t border-gray-150">
                        <!-- Filled dynamically by JS -->
                    </div>

                    <!-- AI Recommendation -->
                    <div class="mt-auto bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl">
                        <p class="text-xs font-bold text-blue-900 mb-1 uppercase tracking-wide">Kesimpulan Sistem</p>
                        <p id="recommendation" class="text-sm text-blue-800 leading-relaxed font-medium">
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
    <div id="loadingState" class="hidden fixed inset-0 z-40 flex items-center justify-center backdrop-blur-sm bg-black/50">
        <div class="bg-white rounded-lg shadow-xl p-8 text-center">
            <svg class="h-12 w-12 text-blue-600 animate-spin mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0c4.418 0 8 3.582 8 8h-2zm15.535-8.172a.5.5 0 00-.707.707L19.586 6l-1.414-1.414a.5.5 0 00-.707.707L18.879 6.707 17.465 8.12a.5.5 0 00.707.707L19.586 7.414l1.414 1.414a.5.5 0 00.707-.707L20.293 7.414l1.414-1.414a.5.5 0 000-.707z"/>
            </svg>
            <p class="text-gray-900 font-medium" id="loadingText">Memproses...</p>
        </div>
    </div>

    <div id="errorState" class="hidden fixed inset-0 z-40 flex items-center justify-center backdrop-blur-sm bg-black/50">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
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

    <!-- Success Modal -->
    <div id="successState" class="hidden fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-black/50">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mx-auto mb-4">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Berhasil</h3>
            <div id="successMessage" class="text-gray-600 text-center text-sm mb-6">
                <!-- Success message filled by JS -->
            </div>
            <button onclick="closeSuccess()" class="w-full px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700">
                OK
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
    if (!uploadedFile) {
        showError('Pilih file terlebih dahulu');
        return;
    }

    const formData = new FormData();
    formData.append('boq_file', uploadedFile);

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

        if (!response.ok || !data.success) {
            throw new Error(data.data?.errors?.[0] || data.message || 'Gagal mengekstrak preview');
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

    if (!document.getElementById('client_id').value) {
        showError('Pilih klien terlebih dahulu');
        return;
    }

    showLoading('Menyimpan penawaran draft dan menganalisis dengan AI DSS...');

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
            })
        });

        const draftData = await draftResponse.json();

        if (!draftResponse.ok) {
            throw new Error(draftData.message || 'Gagal menyimpan draft penawaran');
        }

        penawaranId = draftData.data.penawaran_id;

        // Now analyze with DSS
        showLoading('Menganalisis data dengan AI...');
        
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
    const prediksi = Math.round(data.predictions.lr || data.ai_prediksi_lr);
    const selisih = data.grand_total - prediksi; // Positif jika Penawaran > Prediksi AI (Untung)
    const lrVariance = Math.abs((selisih / data.grand_total) * 100).toFixed(1);
    
    let selisihWarna, selisihBg, selisihLabel, badgeBgClass, badgeTextSymbol;
    let iconHtml;
    
    if (selisih < 0) {
        // Rugi (Merah)
        selisihWarna = 'text-red-600';
        selisihBg = 'bg-red-50 border-red-200';
        selisihLabel = 'Potensi Overrun / Risiko Defisit (Rugi)';
        badgeBgClass = 'bg-red-100 text-red-700';
        badgeTextSymbol = `-${lrVariance}% Risiko Rugi`;
        iconHtml = '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
    } else if (lrVariance < 10) {
        // Untung tapi tipis / warning (Kuning)
        selisihWarna = 'text-amber-600';
        selisihBg = 'bg-amber-50 border-amber-200';
        selisihLabel = 'Estimasi Margin Keuntungan Sangat Tipis (Rawan)';
        badgeBgClass = 'bg-amber-100 text-amber-700';
        badgeTextSymbol = `+${lrVariance}% Untung (Tipis)`;
        iconHtml = '<svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
    } else {
        // Untung Sehat / Aman (Hijau)
        selisihWarna = 'text-green-600';
        selisihBg = 'bg-green-50 border-green-200';
        selisihLabel = 'Estimasi Margin Keuntungan Sehat (Aman)';
        badgeBgClass = 'bg-green-100 text-green-700';
        badgeTextSymbol = `+${lrVariance}% Untung`;
        iconHtml = '<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    }
    
    document.getElementById('lrPrediction').textContent = 'Rp ' + prediksi.toLocaleString('id-ID');
    document.getElementById('lrVarianceNominal').textContent = 'Rp ' + Math.abs(selisih).toLocaleString('id-ID');
    document.getElementById('lrVarianceNominal').className = 'text-xl font-bold ' + selisihWarna;
    
    document.getElementById('lrVariance').textContent = badgeTextSymbol;
    document.getElementById('lrVariance').className = 'text-xs font-bold px-2 py-0.5 rounded-full mb-0.5 ' + badgeBgClass;
    
    document.getElementById('varianceLabel').textContent = selisihLabel;
    document.getElementById('varianceContainer').className = 'flex items-center p-4 rounded-xl border shadow-sm ' + selisihBg;
    
    document.getElementById('varianceIcon').innerHTML = iconHtml;
    document.getElementById('varianceIcon').className = 'flex-shrink-0 bg-white p-2.5 rounded-full shadow-sm';

    // Multi-Model Comparison rendering
    const grandTotal = data.grand_total;
    const modelsData = [
        {
            id: 'lr',
            name: 'Linear Regression',
            rank: 1,
            r2: '95.5%',
            mape: '25.8%',
            mse: '0.0013',
            rmse: '0.0359',
            pred: data.predictions?.lr || 0,
            time: data.predictions_meta?.linear_regression?.time_ms || 0,
            color: 'from-blue-500 to-cyan-500'
        },
        {
            id: 'rf',
            name: 'Random Forest Ensemble',
            rank: 2,
            r2: '94.6%',
            mape: '17.7%',
            mse: '0.0015',
            rmse: '0.0393',
            pred: data.predictions?.rf || 0,
            time: data.predictions_meta?.random_forest?.time_ms || 0,
            color: 'from-purple-500 to-indigo-500'
        },
        {
            id: 'xgb',
            name: 'XGBoost Boosting',
            rank: 3,
            r2: '94.5%',
            mape: '17.9%',
            mse: '0.0016',
            rmse: '0.0399',
            pred: data.predictions?.xgb || 0,
            time: data.predictions_meta?.xgboost?.time_ms || 0,
            color: 'from-orange-500 to-red-500'
        },
        {
            id: 'dl',
            name: 'Deep Learning (ANN)',
            rank: 4,
            r2: '95.0%',
            mape: '29.7%',
            mse: '0.0015',
            rmse: '0.0381',
            pred: data.predictions?.dl || 0,
            time: data.predictions_meta?.deep_learning?.time_ms || 0,
            color: 'from-pink-500 to-rose-500'
        },
        {
            id: 'ma',
            name: 'Moving Average',
            rank: 5,
            r2: '93.7%',
            mape: '18.3%',
            mse: '0.0018',
            rmse: '0.0423',
            pred: data.predictions?.ma || 0,
            time: data.predictions_meta?.moving_average?.time_ms || 0,
            color: 'from-gray-500 to-slate-500'
        }
    ];

    let compHtml = `
        <h4 class="text-xs font-extrabold text-gray-900 uppercase tracking-wide flex items-center gap-2 mb-2">
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            Perbandingan Estimasi Multi-Model AI
        </h4>
        <p class="text-[10px] text-gray-500 mb-3 leading-tight">Model diurutkan berdasarkan peringkat performa global hasil uji akademik (*Multi-Metric Borda Count*).</p>
        <div class="space-y-2.5">
    `;

    modelsData.forEach(m => {
        const mSelisih = grandTotal - m.pred;
        const mPersen = Math.abs((mSelisih / grandTotal) * 100).toFixed(1);
        const mIsUntung = mSelisih >= 0;
        
        const badgeBg = mIsUntung ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200';
        const badgeText = mIsUntung ? `+${mPersen}% Untung` : `-${mPersen}% Risiko Rugi`;
        
        const rankColors = m.rank === 1 ? 'bg-amber-100 text-amber-800 border border-amber-200 font-extrabold'
                         : m.rank === 2 ? 'bg-purple-100 text-purple-800 border border-purple-200 font-bold'
                         : m.rank === 3 ? 'bg-orange-100 text-orange-800 border border-orange-200 font-bold'
                         : m.rank === 4 ? 'bg-pink-100 text-pink-800 border border-pink-200 font-bold'
                         : 'bg-slate-100 text-slate-700 border border-slate-200 font-semibold';
        
        compHtml += `
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-3.5 rounded-xl border border-gray-150 bg-white hover:border-purple-300 hover:shadow-md transition duration-200 gap-2 relative overflow-hidden group">
                <div class="absolute top-0 left-0 bottom-0 w-1 bg-gradient-to-b ${m.color}"></div>
                
                <div class="flex items-center gap-3 pl-2 flex-1 min-w-0">
                    <div class="w-8 h-8 rounded-full ${rankColors} flex items-center justify-center text-xs flex-shrink-0">
                        #${m.rank}
                    </div>
                    
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs sm:text-sm font-bold text-gray-900 truncate">${m.name}</span>
                        </div>
                        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                            <span class="text-[9px] px-1.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-medium font-mono border border-blue-100">R²: ${m.r2}</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-medium font-mono border border-indigo-100">MAPE: ${m.mape}</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded-md bg-purple-50 text-purple-700 font-medium font-mono border border-purple-100">MSE: ${m.mse}</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded-md bg-pink-50 text-pink-700 font-medium font-mono border border-pink-100">RMSE: ${m.rmse}</span>
                            <div class="flex items-center gap-1 text-[9px] text-gray-500 font-sans ml-1 bg-gray-50 border border-gray-200 rounded-md px-1.5 py-0.5">
                                <svg class="w-3 h-3 text-purple-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <span>${m.time.toFixed(1)} ms</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 pl-11 sm:pl-0 w-full sm:w-auto justify-between sm:justify-end flex-shrink-0">
                    <div class="text-right">
                        <span class="block text-xs sm:text-sm font-black text-gray-900">Rp ${m.pred.toLocaleString('id-ID')}</span>
                    </div>
                    <span class="text-[10px] sm:text-xs font-bold px-2.5 py-1 rounded-lg border ${badgeBg} min-w-[95px] text-center shadow-sm">
                        ${badgeText}
                    </span>
                </div>
            </div>
        `;
    });

    compHtml += `</div>`;
    document.getElementById('multiModelComparison').innerHTML = compHtml;

    // Recommendation
    document.getElementById('recommendation').textContent = data.recommendation || 'Rekomendasi tidak tersedia';
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
                user_decision: decision,
                notes: ''
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Gagal menyimpan keputusan');
        }

        // Success - show detailed message with materials info
        let message = data.message || 'Keputusan berhasil disimpan.';
        
        if (decision === 'approve' && data.data?.materials_created > 0) {
            message = `✓ ${message} <br><strong>${data.data.materials_created} material baru telah dibuat otomatis.</strong>`;
            
            if (data.data.materials_info?.materials?.length > 0) {
                message += '<br><br><strong>Material Baru:</strong><ul style="margin-top: 0.5rem; margin-left: 1rem;">';
                data.data.materials_info.materials.forEach(m => {
                    message += `<li>${m.kode} - ${m.nama} (${m.satuan})</li>`;
                });
                message += '</ul>';
            }
        }

        showSuccessModal(message);
        setTimeout(() => {
            window.location.href = '{{ route("penawaran.index") }}';
        }, 2000);

    } catch (error) {
        showError(error.message);
        hideLoading();  // Make sure loading is hidden on error too
    } finally {
        // Force hide any lingering loading state
        setTimeout(() => {
            hideLoading();
        }, 100);
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

function showSuccessModal(message) {
    document.getElementById('successMessage').innerHTML = message;
    document.getElementById('successState').classList.remove('hidden');
}

function closeSuccess() {
    document.getElementById('successState').classList.add('hidden');
}

function showSuccess(message) {
    showToast(message, 'success', 4000);
}

// ============================================

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
