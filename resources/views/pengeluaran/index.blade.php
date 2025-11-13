@extends('layouts.app')

@section('title', 'Data Pengeluaran')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Data Pengeluaran</h1>
            <p class="text-gray-600 mt-2">Kelola semua pengeluaran proyek</p>
        </div>
        <a href="{{ route('pengeluaran.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Pengeluaran</span>
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
            <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Total Pengeluaran</p>
            <p class="text-3xl font-bold text-blue-600 mt-2" title="Rp {{ number_format($pengeluaran->sum('jumlah'), 0, ',', '.') }}">
                {{ $formatHelper->formatCurrencyCompact($pengeluaran->sum('jumlah')) }}
            </p>
            <p class="text-xs text-gray-600 mt-1">Dari semua kategori</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Total Pengeluaran</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pengeluaran->count() }}</p>
            <p class="text-xs text-green-600 mt-1">Tercatat di sistem</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm">Data Terbaru</p>
            <p class="text-sm font-medium text-gray-900 mt-2">
                @if ($pengeluaran->count() > 0)
                    {{ $pengeluaran->first()->proyek->nama ?? '-' }}
                @else
                    Belum ada data
                @endif
            </p>
            <p class="text-xs text-gray-600 mt-1">Pengeluaran terakhir</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <input type="text" id="searchInput" placeholder="Cari pengeluaran by proyek atau kategori..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button onclick="resetSearch()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Reset
            </button>
        </div>
    </div>

    <!-- Pengeluaran Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if ($pengeluaran->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Proyek</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Deskripsi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Dibuat Oleh</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Bukti</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="pengeluaranTable">
                    @foreach ($pengeluaran as $item)
                        <tr class="hover:bg-gray-50 transition pengeluaran-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->tanggal->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('proyek.show', $item->proyek_id) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $item->proyek->nama ?? '-' }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $item->getKategoriColor() }}">
                                    {{ $item->getKategoriLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 max-w-xs">{{ Str::limit($item->deskripsi, 40) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900 text-right" title="Rp {{ number_format($item->jumlah, 0, ',', '.') }}">
                                    {{ $formatHelper->formatCurrencyCompact($item->jumlah) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $item->creator->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($item->bukti_file)
                                    <a href="{{ asset('storage/' . $item->bukti_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium text-xs inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('pengeluaran.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-800 font-medium text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button type="button" onclick="openDeleteModal({{ $item->id }}, '{{ $item->proyek->nama }}', {{ $item->jumlah }})" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST" style="display:none;">
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
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-600 text-lg font-medium">Belum ada pengeluaran</p>
                <p class="text-gray-500 text-sm mt-2">Mulai dengan menambahkan pengeluaran pertama</p>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($pengeluaran->hasPages())
    <div class="mt-8">
        {{ $pengeluaran->links() }}
    </div>
    @endif

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="from" value="index">
    </form>

    <script>
        function openDeleteModal(id, proyek, jumlah) {
            const jumlahFormatted = 'Rp ' + jumlah.toLocaleString('id-ID');
            const message = `Pengeluaran untuk "${proyek}" dengan jumlah ${jumlahFormatted}.\n\nTindakan ini tidak dapat dibatalkan.`;
            
            showConfirm(message, 'Hapus Pengeluaran?', function() {
                document.getElementById('delete-form').action = `/pengeluaran/${id}`;
                document.getElementById('delete-form').submit();
            });
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('.pengeluaran-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });

        function resetSearch() {
            document.getElementById('searchInput').value = '';
            document.querySelectorAll('.pengeluaran-row').forEach(row => {
                row.style.display = '';
            });
        }
    </script>
@endsection
