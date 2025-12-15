@extends('layouts.app')

@section('title', 'Item Penawaran Management')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Item Penawaran Management</h1>
            <p class="text-gray-600 mt-2">Kelola data item penawaran (barang, jasa, tol) dan harga dari supplier</p>
        </div>
        <div class="flex gap-3">
            <button onclick="openImportModal()" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Import Material</span>
            </button>
            <a href="{{ route('material.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Item Penawaran</span>
            </a>
        </div>
    </div>

    <!-- Import Success Display -->
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold text-green-900">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Import Warnings Display -->
    @if (session('warning'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold text-yellow-900 mb-2">{{ session('warning') }}</p>
                    @php
                        $sessionErrors = session('errors');
                    @endphp
                    @if (is_array($sessionErrors) && count($sessionErrors) > 0)
                        <ul class="text-sm text-yellow-800 space-y-1">
                            @foreach ($sessionErrors as $error)
                                <li class="flex items-start gap-2">
                                    <span class="text-yellow-600 mt-0.5">•</span>
                                    <span>{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @elseif (is_array(session('errors')) && count(session('errors')) > 0)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <h3 class="font-semibold text-red-900 mb-2">Terdapat kesalahan dalam import:</h3>
                    <ul class="text-sm text-red-800 space-y-1">
                        @foreach (session('errors') as $error)
                            <li class="flex items-start gap-2">
                                <span class="text-red-600 mt-0.5">•</span>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Total Material</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalMaterials }}</p>
            <p class="text-xs text-gray-600 mt-1">Terdaftar di sistem</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Material dengan Tracking</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $trackInventory }}</p>
            <p class="text-xs text-green-600 mt-1">Material Barang yang tracking stok</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <p class="text-gray-600 text-sm">Material Non-Stok</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $nonTrackInventory }}</p>
            <p class="text-xs text-gray-600 mt-1">Jasa, Tol, dan lainnya</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <input type="text" id="searchInput" placeholder="Cari material by nama atau supplier..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button onclick="resetSearch()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Reset
            </button>
        </div>
    </div>

    <!-- Item Penawaran Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if ($materials->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Satuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="materialTable">
                    @foreach ($materials as $material)
                        @php
                            $stok = $material->inventory?->stok ?? 0;
                            $hasStok = $stok > 0;
                        @endphp
                        <tr class="hover:bg-gray-50 transition material-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono text-gray-600">{{ $material->kode ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $material->nama }}</div>
                                        @if ($material->needsInventoryTracking() && !$hasStok)
                                            <div class="inline-block mt-1 px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">
                                                Stok: 0 - Tidak Tersedia
                                            </div>
                                        @elseif ($material->needsInventoryTracking())
                                            <div class="inline-block mt-1 px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">
                                                Stok: {{ number_format($stok, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-3 py-1 
                                    @if($material->type === 'BARANG') bg-blue-100 text-blue-800
                                    @elseif($material->type === 'JASA') bg-green-100 text-green-800
                                    @elseif($material->type === 'TOL') bg-orange-100 text-orange-800
                                    @else bg-gray-100 text-gray-800
                                    @endif
                                    rounded-full text-xs font-medium">
                                    {{ $material->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $material->supplier->nama ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ $material->satuan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-green-600" title="Rp {{ number_format($material->harga, 0, ',', '.') }}">
                                    {{ $formatHelper->formatCurrencyCompact($material->harga) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex gap-2">
                                    <a href="{{ route('material.show', $material->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('material.edit', $material->id) }}" class="text-yellow-600 hover:text-yellow-800 font-medium text-sm" title="Edit Material">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button onclick="showConfirm('Yakin ingin menghapus material ini? Data tidak bisa dipulihkan.', 'Hapus Material', function() { document.getElementById('deleteForm{{ $material->id }}').submit(); })" class="text-red-600 hover:text-red-800 font-medium text-sm" title="Hapus Material">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    <form id="deleteForm{{ $material->id }}" action="{{ route('material.destroy', $material->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
            <!-- No search results message -->
            <div id="noResultsMessage" class="hidden p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Data tidak tersedia</h3>
                <p class="text-gray-600">Tidak ada item penawaran yang sesuai dengan pencarian Anda.</p>
            </div>
            
            <!-- Pagination Links -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $materials->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada item penawaran</h3>
                <p class="text-gray-600 mb-6">Mulai dengan menambahkan item penawaran baru untuk dikelola inventorynya.</p>
                <a href="{{ route('material.create') }}" class="inline-flex px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Item Penawaran
                </a>
            </div>
        @endif
    </div>

    <script>
        function openImportModal() {
            document.getElementById('importModal').style.display = 'flex';
        }

        function closeImportModal() {
            document.getElementById('importModal').style.display = 'none';
            document.getElementById('importForm').reset();
            document.getElementById('fileName').textContent = '';
        }

        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.material-row');
            let visibleRows = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            const noResultsMessage = document.getElementById('noResultsMessage');
            if (visibleRows === 0 && rows.length > 0) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        });

        function resetSearch() {
            document.getElementById('searchInput').value = '';
            document.querySelectorAll('.material-row').forEach(row => {
                row.style.display = '';
            });
            document.getElementById('noResultsMessage').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('importModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImportModal();
            }
        });

        function updateFileName(input) {
            const fileName = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileName.textContent = '✓ File dipilih: ' + input.files[0].name;
                fileName.classList.remove('text-red-600');
                fileName.classList.add('text-green-600');
            } else {
                fileName.textContent = '';
            }
        }
    </script>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Import Material dari Excel</h3>

            <form id="importForm" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih File Excel (.xlsx)
                    </label>
                    <div class="relative">
                        <input 
                            type="file" 
                            id="file" 
                            name="file" 
                            accept=".xlsx,.xls" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 transition cursor-pointer"
                            onchange="updateFileName(this)"
                        >
                        <p id="fileName" class="mt-2 text-sm text-gray-700"></p>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-900 text-sm mb-2">Format Excel:</h4>
                    <p class="text-xs text-blue-800 mb-3">No, Kategori, Kode, Item, Satuan, Supplier, Harga, Qty</p>
                    <p class="text-xs text-blue-700 space-y-1">
                        <span class="block"><strong>Kategori:</strong> BARANG, JASA, TOL, atau LAINNYA</span>
                        <span class="block"><strong>Supplier:</strong> Hanya untuk BARANG (kosongkan untuk JASA/TOL/LAINNYA)</span>
                        <span class="block"><strong>Harga:</strong> Angka tanpa simbol (contoh: 50000)</span>
                        <span class="block"><strong>Qty:</strong> Jumlah stok (untuk BARANG)</span>
                    </p>
                </div>

                <div class="flex gap-3">
                    <button 
                        type="button" 
                        onclick="previewImportFile()"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium"
                    >
                        Preview
                    </button>
                    <button 
                        type="button" 
                        onclick="closeImportModal()" 
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium"
                    >
                        Batal
                    </button>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-3">Belum punya template?</p>
                    <a href="{{ route('material.export-template') }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Unduh Template Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal for Duplicates -->
    <div id="confirmModal" style="position: fixed; inset: 0; z-index: 50; background-color: rgba(0,0,0,0.5); display: none; flex; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-width: 800px; width: 100%; margin: 0 16px; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column;">
            <!-- Header -->
            <div style="background: linear-gradient(to right, #2563eb, #4f46e5); padding: 24px; color: white;">
                <div style="display: flex; gap: 12px; align-items: center;">
                    <div style="background: rgba(255,255,255,0.2); padding: 8px; border-radius: 8px;">
                        <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 style="font-size: 18px; font-weight: bold; margin: 0;">Preview Import</h3>
                        <p style="color: #dbeafe; font-size: 14px; margin: 4px 0 0 0;">Tinjau data sebelum mengimport</p>
                    </div>
                </div>
            </div>
            
            <!-- Content - Scrollable -->
            <div id="confirmContent" style="flex: 1; overflow-y: auto; padding: 24px; background: white;">
                <!-- Akan diisi secara dinamis oleh JavaScript -->
            </div>

            <!-- Footer -->
            <div style="display: flex; gap: 12px; padding: 16px 24px; background: #f3f4f6; border-top: 1px solid #e5e7eb;">
                <button 
                    type="button" 
                    onclick="confirmImport()"
                    style="flex: 1; padding: 12px 16px; background: #16a34a; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;"
                    onmouseover="this.style.background='#15803d'"
                    onmouseout="this.style.background='#16a34a'"
                >
                    <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Konfirmasi Import
                </button>
                <button 
                    type="button" 
                    onclick="closeConfirmModal()"
                    style="flex: 1; padding: 12px 16px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 8px; font-weight: 500; cursor: pointer;"
                    onmouseover="this.style.background='#f9fafb'"
                    onmouseout="this.style.background='white'"
                >
                    Batal
                </button>
            </div>
        </div>
    </div>

    <script>
        let previewData = null;

        function openImportModal() {
            document.getElementById('importModal').style.display = 'flex';
        }

        function closeImportModal() {
            document.getElementById('importModal').style.display = 'none';
            document.getElementById('importForm').reset();
            document.getElementById('fileName').textContent = '';
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').style.display = 'none';
            previewData = null;
        }

        function updateFileName(input) {
            const fileName = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileName.textContent = '✓ File dipilih: ' + input.files[0].name;
                fileName.classList.remove('text-red-600');
                fileName.classList.add('text-green-600');
            } else {
                fileName.textContent = '';
            }
        }

        async function previewImportFile() {
            const fileInput = document.getElementById('file');
            const fileNameEl = document.getElementById('fileName');
            
            if (!fileInput.files || !fileInput.files[0]) {
                // Highlight input file dengan border merah
                fileInput.classList.add('border-red-500', 'ring-2', 'ring-red-200');
                fileInput.classList.remove('border-gray-300');
                fileNameEl.textContent = '⚠️ File harus dipilih terlebih dahulu';
                fileNameEl.classList.add('text-red-600');
                fileNameEl.classList.remove('text-green-600', 'text-gray-700');
                
                // Focus ke input
                fileInput.focus();
                return;
            }
            
            // Reset styling jika sudah ada file
            fileInput.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
            fileInput.classList.add('border-gray-300');

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            try {
                const response = await fetch('{{ route("material.import-preview") }}', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                    return;
                }

                previewData = {
                    file: fileInput.files[0],
                    preview: data
                };

                showConfirmModal(data);
                closeImportModal();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        function showConfirmModal(data) {
            const content = document.getElementById('confirmContent');
            let html = '';

            const totalItems = data.totalDuplicates + data.totalNew;
            const hasErrors = data.errors && data.errors.length > 0;
            
            // Stats Cards
            html += `
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%); border: 1px solid #86efac; border-radius: 12px; padding: 16px; text-align: center;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #bbf7d0; border-radius: 50%; margin-bottom: 8px;">
                            <svg style="width: 20px; height: 20px; color: #16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div style="font-size: 24px; font-weight: bold; color: #15803d;">${data.totalNew}</div>
                        <div style="font-size: 12px; color: #16a34a; font-weight: 500; margin-top: 4px;">Item Baru</div>
                    </div>
                    <div style="background: linear-gradient(135deg, #dbeafe 0%, #f0f9ff 100%); border: 1px solid #93c5fd; border-radius: 12px; padding: 16px; text-align: center;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #bfdbfe; border-radius: 50%; margin-bottom: 8px;">
                            <svg style="width: 20px; height: 20px; color: #2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div style="font-size: 24px; font-weight: bold; color: #1d4ed8;">${data.totalDuplicates}</div>
                        <div style="font-size: 12px; color: #2563eb; font-weight: 500; margin-top: 4px;">Akan Update</div>
                    </div>
                    <div style="background: linear-gradient(135deg, ${hasErrors ? '#fee2e2 0%, #fef2f2 100%' : '#f3f4f6 0%, #f9fafb 100%'}); border: 1px solid ${hasErrors ? '#fca5a5' : '#e5e7eb'}; border-radius: 12px; padding: 16px; text-align: center;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: ${hasErrors ? '#fecaca' : '#e5e7eb'}; border-radius: 50%; margin-bottom: 8px;">
                            <svg style="width: 20px; height: 20px; color: ${hasErrors ? '#dc2626' : '#9ca3af'};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div style="font-size: 24px; font-weight: bold; color: ${hasErrors ? '#b91c1c' : '#9ca3af'};">${hasErrors ? data.errors.length : 0}</div>
                        <div style="font-size: 12px; color: ${hasErrors ? '#dc2626' : '#9ca3af'}; font-weight: 500; margin-top: 4px;">Error</div>
                    </div>
                </div>
            `;

            // Error Alert
            if (hasErrors) {
                html += `
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                        <div style="display: flex; gap: 12px;">
                            <div style="background: #fee2e2; padding: 6px; border-radius: 6px; flex-shrink: 0;">
                                <svg style="width: 16px; height: 16px; color: #dc2626;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div style="flex: 1;">
                                <h4 style="font-size: 14px; font-weight: 600; color: #7f1d1d; margin: 0 0 8px 0;">Baris Bermasalah</h4>
                                <div style="font-size: 14px; color: #991b1b; max-height: 96px; overflow-y: auto;">
                                    ${data.errors.map(err => `<p style="margin: 4px 0; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">• ${err}</p>`).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // New Items Section
            if (data.newItems && data.newItems.length > 0) {
                html += `
                    <div style="margin-bottom: 24px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                            <div style="width: 12px; height: 12px; background: #16a34a; border-radius: 50%;"></div>
                            <h4 style="font-weight: 600; color: #111827; margin: 0; flex: 1;">Item Baru</h4>
                            <span style="font-size: 12px; color: #6b7280; background: #f3f4f6; padding: 4px 8px; border-radius: 9999px;">${data.newItems.length} item</span>
                        </div>
                        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
                            <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                                <thead style="background: linear-gradient(to right, #f3f4f6, #f9fafb);">
                                    <tr>
                                        <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase;">Nama Item</th>
                                        <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase;">Supplier</th>
                                        <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase;">Harga</th>
                                        <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase;">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.newItems.slice(0, 10).map((item, idx) => `
                                        <tr style="background: ${idx % 2 === 0 ? 'white' : '#fafbfc'}; border-bottom: 1px solid #e5e7eb;">
                                            <td style="padding: 12px 16px;"><span style="font-weight: 500; color: #111827;">${item.nama}</span></td>
                                            <td style="padding: 12px 16px; color: #4b5563;">${item.supplier}</td>
                                            <td style="padding: 12px 16px; text-align: right; font-weight: 500; color: #111827;">Rp ${Number(item.harga).toLocaleString('id-ID')}</td>
                                            <td style="padding: 12px 16px; text-align: right;">
                                                <span style="display: inline-block; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 500; background: #dcfce7; color: #166534;">
                                                    ${Number(item.qty).toLocaleString('id-ID')}
                                                </span>
                                            </td>
                                        </tr>
                                    `).join('')}
                                    ${data.newItems.length > 10 ? `
                                        <tr style="background: #f9fafb;">
                                            <td colspan="4" style="padding: 8px 16px; text-align: center; font-size: 12px; color: #6b7280;">
                                                ...dan ${data.newItems.length - 10} item lainnya
                                            </td>
                                        </tr>
                                    ` : ''}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }

            // Update Items Section
            if (data.duplicates && data.duplicates.length > 0) {
                html += `
                    <div style="margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                            <div style="width: 12px; height: 12px; background: #3b82f6; border-radius: 50%;"></div>
                            <h4 style="font-weight: 600; color: #111827; margin: 0; flex: 1;">Item Update</h4>
                            <span style="font-size: 12px; color: #6b7280; background: #f3f4f6; padding: 4px 8px; border-radius: 9999px;">${data.duplicates.length} item</span>
                        </div>
                        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
                            <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                                <thead style="background: linear-gradient(to right, #f3f4f6, #f9fafb);">
                                    <tr>
                                        <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase;">Nama Item</th>
                                        <th style="padding: 12px 16px; text-align: center; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase;">Perubahan Harga</th>
                                        <th style="padding: 12px 16px; text-align: center; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase;">Tambah Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.duplicates.slice(0, 10).map((dup, idx) => `
                                        <tr style="background: ${idx % 2 === 0 ? 'white' : '#fafbfc'}; border-bottom: 1px solid #e5e7eb;">
                                            <td style="padding: 12px 16px;">
                                                <span style="font-weight: 500; color: #111827;">${dup.nama}</span>
                                                <span style="display: block; color: #9ca3af; font-size: 12px;">${dup.supplier}</span>
                                            </td>
                                            <td style="padding: 12px 16px; text-align: center;">
                                                ${dup.priceChanged ? `
                                                    <div style="display: flex; align-items: center; justify-content: center; gap: 4px;">
                                                        <span style="color: #9ca3af; text-decoration: line-through; font-size: 12px;">Rp ${Number(dup.oldPrice).toLocaleString('id-ID')}</span>
                                                        <svg style="width: 12px; height: 12px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                        <span style="color: #16a34a; font-weight: 600; font-size: 12px;">Rp ${Number(dup.newPrice).toLocaleString('id-ID')}</span>
                                                    </div>
                                                ` : `<span style="color: #9ca3af; font-size: 12px;">—</span>`}
                                            </td>
                                            <td style="padding: 12px 16px; text-align: center;">
                                                ${dup.addStok > 0 ? `
                                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; background: #dbeafe; color: #1e40af;">
                                                        +${Number(dup.addStok).toLocaleString('id-ID')}
                                                    </span>
                                                ` : `<span style="color: #9ca3af; font-size: 12px;">—</span>`}
                                            </td>
                                        </tr>
                                    `).join('')}
                                    ${data.duplicates.length > 10 ? `
                                        <tr style="background: #f9fafb;">
                                            <td colspan="3" style="padding: 8px 16px; text-align: center; font-size: 12px; color: #6b7280;">
                                                ...dan ${data.duplicates.length - 10} item lainnya
                                            </td>
                                        </tr>
                                    ` : ''}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }

            // Empty State
            if (totalItems === 0 && !hasErrors) {
                html += `
                    <div style="text-align: center; padding: 48px 0;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background: #f3f4f6; border-radius: 50%; margin-bottom: 16px;">
                            <svg style="width: 32px; height: 32px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 style="font-size: 18px; font-weight: 500; color: #111827; margin: 0 0 4px 0;">Tidak Ada Data</h4>
                        <p style="color: #6b7280; margin: 0;">File tidak berisi data yang valid untuk diimport</p>
                    </div>
                `;
            }

            content.innerHTML = html;
            document.getElementById('confirmModal').style.display = 'flex';
        }

        async function confirmImport() {
            if (!previewData || !previewData.file) {
                alert('File tidak ditemukan');
                return;
            }

            const formData = new FormData();
            formData.append('file', previewData.file);
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            try {
                const response = await fetch('{{ route("material.import") }}', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Import gagal');
                }

                // Show success modal with detailed results
                showSuccessModal(data);
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        function showSuccessModal(data) {
            closeConfirmModal();
            
            const stats = data.stats;
            const hasErrors = data.errors && data.errors.length > 0;
            
            // Group items by type
            const baruItems = data.items.filter(item => item.type === 'baru');
            const stokItems = data.items.filter(item => item.type === 'stok_ditambah');

            let detailsHtml = '';
            
            // Show details in a cleaner table format
            if (baruItems.length > 0 || stokItems.length > 0) {
                detailsHtml = `
                    <div style="background: #f3f4f6; border-radius: 8px; padding: 16px; max-height: 240px; overflow-y: auto;">
                        <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                            <thead>
                                <tr style="text-align: left; color: #6b7280; font-size: 12px; text-transform: uppercase;">
                                    <th style="padding-bottom: 8px;">Item</th>
                                    <th style="padding-bottom: 8px; text-align: center;">Status</th>
                                    <th style="padding-bottom: 8px; text-align: right;">Qty</th>
                                </tr>
                            </thead>
                            <tbody style="border-top: 1px solid #e5e7eb;">
                                ${baruItems.map(item => `
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="padding: 8px 0; color: #111827;">${item.nama}</td>
                                        <td style="padding: 8px 0; text-align: center;"><span style="display: inline-block; padding: 4px 8px; background: #dcfce7; color: #166534; border-radius: 4px; font-size: 12px;">Baru</span></td>
                                        <td style="padding: 8px 0; text-align: right; color: #4b5563;">${item.qty > 0 ? Number(item.qty).toLocaleString('id-ID') : '-'}</td>
                                    </tr>
                                `).join('')}
                                ${stokItems.map(item => `
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="padding: 8px 0; color: #111827;">${item.nama}</td>
                                        <td style="padding: 8px 0; text-align: center;"><span style="display: inline-block; padding: 4px 8px; background: #dbeafe; color: #1e40af; border-radius: 4px; font-size: 12px;">Stok +</span></td>
                                        <td style="padding: 8px 0; text-align: right; color: #2563eb; font-weight: 500;">+${Number(item.qty).toLocaleString('id-ID')}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }

            // Error section if any
            let errorsHtml = '';
            if (hasErrors) {
                errorsHtml = `
                    <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                        <div style="display: flex; gap: 8px;">
                            <svg style="width: 20px; height: 20px; color: #d97706; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p style="font-size: 14px; font-weight: 500; color: #b45309; margin: 0 0 4px 0;">Beberapa baris dilewati karena error:</p>
                                <ul style="font-size: 12px; color: #92400e; list-style: none; padding: 0; margin: 0;">
                                    ${data.errors.slice(0, 5).map(err => `<li style="margin: 2px 0;">• ${err}</li>`).join('')}
                                    ${data.errors.length > 5 ? `<li style="margin: 2px 0; color: #d97706;">...dan ${data.errors.length - 5} error lainnya</li>` : ''}
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
            }

            const successHTML = `
                <div id="successModal" style="position: fixed; inset: 0; z-index: 50; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;">
                    <div style="background: white; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-width: 500px; width: 100%; margin: 0 16px; overflow: hidden;">
                        <!-- Header -->
                        <div style="background: linear-gradient(to right, #10b981, #059669); padding: 32px 24px; text-align: center; color: white;">
                            <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 50%; margin-bottom: 16px;">
                                <svg style="width: 32px; height: 32px; color: white;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 style="font-size: 24px; font-weight: bold; margin: 0 0 4px 0;">Import Berhasil!</h3>
                            <p style="color: #d1fae5; font-size: 14px; margin: 0;">Data material telah diperbarui</p>
                        </div>
                        
                        <!-- Stats -->
                        <div style="padding: 16px 24px; background: #f3f4f6; border-bottom: 1px solid #f0f0f0; display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                            <div style="text-align: center;">
                                <div style="font-size: 20px; font-weight: bold; color: #111827;">${stats.totalProcessed}</div>
                                <div style="font-size: 12px; color: #6b7280;">Diproses</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 20px; font-weight: bold; color: #16a34a;">${stats.newItems}</div>
                                <div style="font-size: 12px; color: #6b7280;">Item Baru</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 20px; font-weight: bold; color: #2563eb;">${stats.stokAdded}</div>
                                <div style="font-size: 12px; color: #6b7280;">Stok +</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 20px; font-weight: bold; color: #9333ea;">${stats.pricesUpdated}</div>
                                <div style="font-size: 12px; color: #6b7280;">Harga Update</div>
                            </div>
                        </div>
                        
                        <!-- Details -->
                        <div style="padding: 24px;">
                            ${errorsHtml}
                            ${detailsHtml}
                        </div>
                        
                        <!-- Footer -->
                        <div style="padding: 16px 24px; background: #f3f4f6; border-top: 1px solid #e5e7eb;">
                            <button 
                                type="button" 
                                onclick="location.reload()"
                                style="width: 100%; padding: 12px 16px; background: #111827; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 14px;"
                                onmouseover="this.style.background='#1f2937'"
                                onmouseout="this.style.background='#111827'"
                            >
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Refresh Halaman
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', successHTML);
            
            // Close on background click
            document.getElementById('successModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    location.reload();
                }
            });
        }

        document.getElementById('importModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImportModal();
            }
        });

        document.getElementById('confirmModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmModal();
            }
        });
    </script>

@endsection