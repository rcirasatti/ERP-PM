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
                    <p class="text-xs text-blue-800 mb-3">No, Kategori, Item, Satuan, Supplier, Harga, Qty</p>
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
    <div id="confirmModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 text-white flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Preview Import</h3>
                        <p class="text-blue-100 text-sm">Tinjau data sebelum mengimport</p>
                    </div>
                </div>
            </div>
            
            <!-- Content - Scrollable -->
            <div id="confirmContent" class="flex-1 overflow-y-auto p-6">
                <!-- Akan diisi secara dinamis oleh JavaScript -->
            </div>

            <!-- Footer -->
            <div class="flex gap-3 px-6 py-4 bg-gray-50 border-t border-gray-200 flex-shrink-0">
                <button 
                    type="button" 
                    onclick="confirmImport()"
                    class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Konfirmasi Import
                </button>
                <button 
                    type="button" 
                    onclick="closeConfirmModal()" 
                    class="flex-1 px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium"
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
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 text-center">
                        <div class="inline-flex items-center justify-center w-10 h-10 bg-green-100 rounded-full mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-green-700">${data.totalNew}</div>
                        <div class="text-xs text-green-600 font-medium">Item Baru</div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 text-center">
                        <div class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-blue-700">${data.totalDuplicates}</div>
                        <div class="text-xs text-blue-600 font-medium">Akan Update</div>
                    </div>
                    <div class="bg-gradient-to-br ${hasErrors ? 'from-red-50 to-rose-50 border-red-200' : 'from-gray-50 to-slate-50 border-gray-200'} border rounded-xl p-4 text-center">
                        <div class="inline-flex items-center justify-center w-10 h-10 ${hasErrors ? 'bg-red-100' : 'bg-gray-100'} rounded-full mb-2">
                            <svg class="w-5 h-5 ${hasErrors ? 'text-red-600' : 'text-gray-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="text-2xl font-bold ${hasErrors ? 'text-red-700' : 'text-gray-400'}">${hasErrors ? data.errors.length : 0}</div>
                        <div class="text-xs ${hasErrors ? 'text-red-600' : 'text-gray-400'} font-medium">Error</div>
                    </div>
                </div>
            `;

            // Error Alert
            if (hasErrors) {
                html += `
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <div class="bg-red-100 p-1.5 rounded-lg flex-shrink-0">
                                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-red-800 mb-2">Baris Bermasalah</h4>
                                <div class="text-sm text-red-700 space-y-1 max-h-24 overflow-y-auto">
                                    ${data.errors.map(err => `<p class="truncate">• ${err}</p>`).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // New Items Section
            if (data.newItems && data.newItems.length > 0) {
                html += `
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <h4 class="font-semibold text-gray-900">Item Baru</h4>
                            <span class="ml-auto text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">${data.newItems.length} item</span>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <table class="w-full text-sm">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Item</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    ${data.newItems.slice(0, 10).map((item, index) => `
                                        <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50/50'} hover:bg-green-50/50 transition">
                                            <td class="px-4 py-3">
                                                <span class="font-medium text-gray-900">${item.nama}</span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-600">${item.supplier}</td>
                                            <td class="px-4 py-3 text-right font-medium text-gray-900">Rp ${Number(item.harga).toLocaleString('id-ID')}</td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ${Number(item.qty).toLocaleString('id-ID')}
                                                </span>
                                            </td>
                                        </tr>
                                    `).join('')}
                                    ${data.newItems.length > 10 ? `
                                        <tr class="bg-gray-50">
                                            <td colspan="4" class="px-4 py-2 text-center text-sm text-gray-500">
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
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <h4 class="font-semibold text-gray-900">Item Update</h4>
                            <span class="ml-auto text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">${data.duplicates.length} item</span>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <table class="w-full text-sm">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Item</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Perubahan Harga</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tambah Stok</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    ${data.duplicates.slice(0, 10).map((dup, index) => `
                                        <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50/50'} hover:bg-blue-50/50 transition">
                                            <td class="px-4 py-3">
                                                <span class="font-medium text-gray-900">${dup.nama}</span>
                                                <span class="text-gray-400 text-xs block">${dup.supplier}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                ${dup.priceChanged ? `
                                                    <div class="flex items-center justify-center gap-1">
                                                        <span class="text-gray-400 line-through text-xs">Rp ${Number(dup.oldPrice).toLocaleString('id-ID')}</span>
                                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                        <span class="text-green-600 font-semibold text-xs">Rp ${Number(dup.newPrice).toLocaleString('id-ID')}</span>
                                                    </div>
                                                ` : `<span class="text-gray-400 text-xs">—</span>`}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                ${dup.addStok > 0 ? `
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                        +${Number(dup.addStok).toLocaleString('id-ID')}
                                                    </span>
                                                ` : `<span class="text-gray-400 text-xs">—</span>`}
                                            </td>
                                        </tr>
                                    `).join('')}
                                    ${data.duplicates.length > 10 ? `
                                        <tr class="bg-gray-50">
                                            <td colspan="3" class="px-4 py-2 text-center text-sm text-gray-500">
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
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-1">Tidak Ada Data</h4>
                        <p class="text-gray-500">File tidak berisi data yang valid untuk diimport</p>
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
                    <div class="bg-gray-50 rounded-lg p-4 max-h-60 overflow-y-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 text-xs uppercase">
                                    <th class="pb-2">Item</th>
                                    <th class="pb-2 text-center">Status</th>
                                    <th class="pb-2 text-right">Qty</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                ${baruItems.map(item => `
                                    <tr>
                                        <td class="py-2 text-gray-900">${item.nama}</td>
                                        <td class="py-2 text-center"><span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs">Baru</span></td>
                                        <td class="py-2 text-right text-gray-600">${item.qty > 0 ? Number(item.qty).toLocaleString('id-ID') : '-'}</td>
                                    </tr>
                                `).join('')}
                                ${stokItems.map(item => `
                                    <tr>
                                        <td class="py-2 text-gray-900">${item.nama}</td>
                                        <td class="py-2 text-center"><span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs">Stok +</span></td>
                                        <td class="py-2 text-right text-blue-600 font-medium">+${Number(item.qty).toLocaleString('id-ID')}</td>
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
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-amber-800">Beberapa baris dilewati karena error:</p>
                                <ul class="mt-1 text-xs text-amber-700">
                                    ${data.errors.slice(0, 5).map(err => `<li>• ${err}</li>`).join('')}
                                    ${data.errors.length > 5 ? `<li class="text-amber-600">...dan ${data.errors.length - 5} error lainnya</li>` : ''}
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
            }

            const successHTML = `
                <div id="successModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
                    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-8 text-center text-white">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold">Import Berhasil!</h3>
                            <p class="text-green-100 mt-1">Data material telah diperbarui</p>
                        </div>
                        
                        <!-- Stats -->
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="grid grid-cols-4 gap-3 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-gray-900">${stats.totalProcessed}</div>
                                    <div class="text-xs text-gray-500">Diproses</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-green-600">${stats.newItems}</div>
                                    <div class="text-xs text-gray-500">Item Baru</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-blue-600">${stats.stokAdded}</div>
                                    <div class="text-xs text-gray-500">Stok +</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-purple-600">${stats.pricesUpdated}</div>
                                    <div class="text-xs text-gray-500">Harga Update</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Details -->
                        <div class="px-6 py-4">
                            ${errorsHtml}
                            ${detailsHtml}
                        </div>
                        
                        <!-- Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            <button 
                                type="button" 
                                onclick="location.reload()"
                                class="w-full px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition font-medium flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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