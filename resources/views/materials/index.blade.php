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
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $materials->count() }}</p>
            <p class="text-xs text-gray-600 mt-1">Terdaftar di sistem</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Material dengan Tracking</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $materials->where('track_inventory', true)->count() }}</p>
            <p class="text-xs text-green-600 mt-1">Material Barang yang tracking stok</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <p class="text-gray-600 text-sm">Material Non-Stok</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $materials->where('track_inventory', false)->count() }}</p>
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
            <h3 class="text-lg font-bold text-gray-900 mb-6">Import Material dari CSV</h3>

            <form id="importForm" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih File CSV
                    </label>
                    <div class="relative">
                        <input 
                            type="file" 
                            id="file" 
                            name="file" 
                            accept=".csv,.txt" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 transition cursor-pointer"
                            onchange="updateFileName(this)"
                        >
                        <p id="fileName" class="mt-2 text-sm text-gray-700"></p>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-900 text-sm mb-2">Format CSV:</h4>
                    <p class="text-xs text-blue-800 mb-3">No, Kategori, Item, Satuan, Supplier (Hanya BARANG), Harga, Qty, Jumlah</p>
                    <p class="text-xs text-blue-700 space-y-1">
                        <span class="block"><strong>Kategori:</strong> BARANG, JASA, TOL, atau LAINNYA</span>
                        <span class="block"><strong>Supplier:</strong> Hanya untuk BARANG (kosongkan untuk JASA/TOL/LAINNYA)</span>
                        <span class="block"><strong>Harga:</strong> Angka tanpa simbol (contoh: 50000)</span>
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
                        Unduh Template CSV
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal for Duplicates -->
    <div id="confirmModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 p-6 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Preview Import - Konfirmasi Perubahan</h3>
            
            <div id="confirmContent" class="space-y-6">
                <!-- Akan diisi secara dinamis oleh JavaScript -->
            </div>

            <div class="flex gap-3 mt-6 border-t border-gray-200 pt-6">
                <button 
                    type="button" 
                    onclick="confirmImport()"
                    class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium"
                >
                    Lanjutkan Import
                </button>
                <button 
                    type="button" 
                    onclick="closeConfirmModal()" 
                    class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium"
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
            if (!fileInput.files || !fileInput.files[0]) {
                alert('Pilih file terlebih dahulu');
                return;
            }

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

            // Tampilkan error jika ada
            if (data.errors && data.errors.length > 0) {
                html += `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <h4 class="font-semibold text-red-900 mb-2">⚠️ Ada kesalahan:</h4>
                        <ul class="text-sm text-red-800 space-y-1">
                            ${data.errors.map(err => `<li>• ${err}</li>`).join('')}
                        </ul>
                    </div>
                `;
            }

            // Tampilkan item yang duplikat
            if (data.duplicates && data.duplicates.length > 0) {
                html += `
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="font-semibold text-yellow-900 mb-3">🔄 Item yang Sudah Ada (akan diproses)</h4>
                        <div class="space-y-3">
                            ${data.duplicates.map(dup => {
                                let priceAlert = '';
                                if (dup.priceChanged) {
                                    priceAlert = `
                                        <div class="bg-orange-100 border-l-4 border-orange-500 p-2 mt-2">
                                            <span class="text-xs font-semibold text-orange-900">💰 PERUBAHAN HARGA:</span><br/>
                                            <span class="text-xs text-orange-800">
                                                Dari: Rp ${Number(dup.oldPrice).toLocaleString('id-ID')} 
                                                → Ke: Rp ${Number(dup.newPrice).toLocaleString('id-ID')}
                                            </span>
                                        </div>
                                    `;
                                }
                                return `
                                    <div class="bg-white border border-yellow-300 rounded p-3">
                                        <div class="font-medium text-gray-900">${dup.nama}</div>
                                        <div class="text-xs text-gray-600 mt-1">Supplier: ${dup.supplier}</div>
                                        ${dup.addStok > 0 ? `
                                            <div class="bg-blue-100 p-2 mt-2 rounded">
                                                <span class="text-xs font-semibold text-blue-900">📦 STOK AKAN DITAMBAHKAN:</span><br/>
                                                <span class="text-xs text-blue-800">
                                                    Stok sekarang: <strong>${Number(dup.currentStok).toLocaleString('id-ID')}</strong><br/>
                                                    Tambah: <strong>+ ${Number(dup.addStok).toLocaleString('id-ID')}</strong><br/>
                                                    Total baru: <strong>${Number(dup.newStok).toLocaleString('id-ID')}</strong>
                                                </span>
                                            </div>
                                        ` : ''}
                                        ${priceAlert}
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                `;
            }

            // Tampilkan item baru
            if (data.newItems && data.newItems.length > 0) {
                html += `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-semibold text-green-900 mb-3">✨ Item Baru yang Akan Ditambahkan</h4>
                        <div class="space-y-2">
                            ${data.newItems.map(item => `
                                <div class="bg-white border border-green-300 rounded p-2 text-sm">
                                    <strong>${item.nama}</strong> (${item.supplier})
                                    ${item.qty > 0 ? ` - Qty: ${Number(item.qty).toLocaleString('id-ID')}` : ''}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            // Summary
            html += `
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">📊 Summary</h4>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>• Duplikat ditemukan: <strong>${data.totalDuplicates}</strong></li>
                        <li>• Item baru: <strong>${data.totalNew}</strong></li>
                        ${data.errors && data.errors.length > 0 ? `<li>• Error: <strong>${data.errors.length}</strong></li>` : ''}
                    </ul>
                </div>
            `;

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
            let itemsHtml = '';

            // Group items by type
            const baruItems = data.items.filter(item => item.type === 'baru');
            const stokItems = data.items.filter(item => item.type === 'stok_ditambah');

            if (baruItems.length > 0) {
                itemsHtml += `
                    <div class="space-y-2 mb-3">
                        <h4 class="font-semibold text-green-900">✨ Item Baru Ditambahkan (${baruItems.length}):</h4>
                        <ul class="text-sm text-green-800 space-y-1 max-h-40 overflow-y-auto">
                            ${baruItems.map(item => `
                                <li class="flex items-center gap-2">
                                    <span class="text-green-600">✓</span>
                                    <span>${item.nama}${item.qty > 0 ? ` (Qty: ${Number(item.qty).toLocaleString('id-ID')})` : ''}</span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                `;
            }

            if (stokItems.length > 0) {
                itemsHtml += `
                    <div class="space-y-2">
                        <h4 class="font-semibold text-blue-900">📦 Stok Ditambahkan (${stokItems.length}):</h4>
                        <ul class="text-sm text-blue-800 space-y-1 max-h-40 overflow-y-auto">
                            ${stokItems.map(item => `
                                <li class="flex items-center gap-2">
                                    <span class="text-blue-600">+</span>
                                    <span>${item.nama} (+${Number(item.qty).toLocaleString('id-ID')})</span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                `;
            }

            const successHTML = `
                <div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
                    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 p-8">
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Import Berhasil! 🎉</h3>
                            <p class="text-gray-600">Semua data telah diproses dengan sukses</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-lg p-4 text-center border border-blue-200">
                                <div class="text-3xl font-bold text-blue-600">${stats.totalProcessed}</div>
                                <div class="text-xs text-blue-800 mt-1">Total Diproses</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4 text-center border border-green-200">
                                <div class="text-3xl font-bold text-green-600">${stats.newItems}</div>
                                <div class="text-xs text-green-800 mt-1">Item Baru</div>
                            </div>
                            <div class="bg-cyan-50 rounded-lg p-4 text-center border border-cyan-200">
                                <div class="text-3xl font-bold text-cyan-600">${stats.stokAdded}</div>
                                <div class="text-xs text-cyan-800 mt-1">Stok Ditambahkan</div>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4 text-center border border-purple-200">
                                <div class="text-3xl font-bold text-purple-600">${stats.pricesUpdated}</div>
                                <div class="text-xs text-purple-800 mt-1">Harga Diperbarui</div>
                            </div>
                        </div>

                        ${itemsHtml}

                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <button 
                                type="button" 
                                onclick="location.reload()"
                                class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium"
                            >
                                ✓ Selesai - Refresh Halaman
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', successHTML);
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