@extends('layouts.app')

@section('title', 'Pengeluaran Proyek')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengeluaran Proyek</h1>
            <p class="text-gray-600 mt-2">Catat dan kelola pengeluaran untuk setiap proyek</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('finance.dashboard') }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            <button onclick="openModal()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Pengeluaran</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" id="search" placeholder="Cari deskripsi..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select id="filter-proyek" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Proyek</option>
                @foreach($proyeks as $proyek)
                    <option value="{{ $proyek->id }}">{{ $proyek->nama }}</option>
                @endforeach
            </select>
            <select id="filter-kategori" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                <option value="material">Material</option>
                <option value="gaji">Gaji</option>
                <option value="bahan_bakar">Bahan Bakar</option>
                <option value="peralatan">Peralatan</option>
                <option value="lainnya">Lainnya</option>
            </select>
            <button onclick="resetFilters()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Reset Filter</button>
        </div>
    </div>

    <!-- Pengeluaran List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($pengeluarans->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full min-w-max" id="pengeluaran-table">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Proyek</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Dibuat Oleh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($pengeluarans as $pengeluaran)
                        <tr class="hover:bg-gray-50 transition" 
                            data-proyek="{{ $pengeluaran->proyek_id }}" 
                            data-kategori="{{ $pengeluaran->kategori }}"
                            data-search="{{ strtolower($pengeluaran->deskripsi) }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $pengeluaran->tanggal->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('proyek.show', $pengeluaran->proyek_id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                    {{ $pengeluaran->proyek->nama }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $pengeluaran->getKategoriColor() }}">
                                    {{ $pengeluaran->getKategoriLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 max-w-xs">{{ $pengeluaran->deskripsi }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-red-600">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">
                                    {{ $pengeluaran->creator ? $pengeluaran->creator->name : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex gap-2">
                                    <button onclick="editPengeluaran({{ $pengeluaran->id }}, '{{ $pengeluaran->proyek_id }}', '{{ $pengeluaran->tanggal->format('Y-m-d') }}', '{{ $pengeluaran->kategori }}', '{{ addslashes($pengeluaran->deskripsi) }}', '{{ $pengeluaran->jumlah }}')" 
                                            class="text-yellow-600 hover:text-yellow-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('finance.pengeluaran.destroy', $pengeluaran->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                </svg>
                <p class="text-gray-500 text-lg font-medium">Belum ada pengeluaran tercatat</p>
                <p class="text-gray-400 text-sm mt-2">Klik tombol "Tambah Pengeluaran" untuk menambahkan pengeluaran baru</p>
            </div>
        @endif

        <!-- Pagination -->
        @if($pengeluarans->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $pengeluarans->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Add/Edit Pengeluaran -->
    <div id="pengeluaranModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Tambah Pengeluaran</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="pengeluaranForm" method="POST" action="{{ route('finance.pengeluaran.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Proyek <span class="text-red-500">*</span></label>
                        <select name="proyek_id" id="proyek_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Proyek</option>
                            @foreach($proyeks as $proyek)
                                <option value="{{ $proyek->id }}">{{ $proyek->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" id="kategori" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Kategori</option>
                                <option value="material">Material</option>
                                <option value="gaji">Gaji</option>
                                <option value="bahan_bakar">Bahan Bakar</option>
                                <option value="peralatan">Peralatan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" id="deskripsi" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Jelaskan detail pengeluaran..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" id="jumlah" required min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 p-6 border-t border-gray-200">
                    <button type="button" onclick="closeModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Modal Functions
    function openModal() {
        document.getElementById('pengeluaranModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Tambah Pengeluaran';
        document.getElementById('pengeluaranForm').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('pengeluaranForm').action = '{{ route("finance.pengeluaran.store") }}';
    }

    function closeModal() {
        document.getElementById('pengeluaranModal').classList.add('hidden');
    }

    function editPengeluaran(id, proyekId, tanggal, kategori, deskripsi, jumlah) {
        document.getElementById('pengeluaranModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Edit Pengeluaran';
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('pengeluaranForm').action = `/finance/pengeluaran/${id}`;
        
        document.getElementById('proyek_id').value = proyekId;
        document.getElementById('tanggal').value = tanggal;
        document.getElementById('kategori').value = kategori;
        document.getElementById('deskripsi').value = deskripsi;
        document.getElementById('jumlah').value = jumlah;
    }

    // Filter Functions
    const searchInput = document.getElementById('search');
    const proyekFilter = document.getElementById('filter-proyek');
    const kategoriFilter = document.getElementById('filter-kategori');
    const table = document.getElementById('pengeluaran-table');
    const rows = table.querySelectorAll('tbody tr[data-proyek]');

    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const proyekId = proyekFilter.value;
        const kategori = kategoriFilter.value;

        rows.forEach(row => {
            const rowProyek = row.getAttribute('data-proyek');
            const rowKategori = row.getAttribute('data-kategori');
            const rowSearch = row.getAttribute('data-search');

            const matchesSearch = !searchTerm || rowSearch.includes(searchTerm);
            const matchesProyek = !proyekId || rowProyek === proyekId;
            const matchesKategori = !kategori || rowKategori === kategori;

            if (matchesSearch && matchesProyek && matchesKategori) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function resetFilters() {
        searchInput.value = '';
        proyekFilter.value = '';
        kategoriFilter.value = '';
        applyFilters();
    }

    searchInput.addEventListener('input', applyFilters);
    proyekFilter.addEventListener('change', applyFilters);
    kategoriFilter.addEventListener('change', applyFilters);

    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endpush
