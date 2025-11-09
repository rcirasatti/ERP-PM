@extends('layouts.app')

@section('title', 'Riwayat Inventory')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Perubahan Inventory</h1>
            <p class="text-gray-600 mt-2">Log semua perubahan stok material</p>
        </div>
        <a href="{{ route('inventory.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Cari material, jenis, atau catatan..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <select id="jenisFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Semua Jenis --</option>
                <option value="masuk">Masuk</option>
                <option value="keluar">Keluar</option>
            </select>
            
            <button onclick="resetSearch()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Reset
            </button>
        </div>
    </div>

    <!-- Log Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if ($logs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Material</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">User</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="logTable">
                    @foreach ($logs as $log)
                        <tr class="hover:bg-gray-50 transition log-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-600">{{ $log->created_at ? $log->created_at->format('d M Y H:i:s') : '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $log->material->nama ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $log->jenis == 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($log->jenis) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold {{ $log->jenis == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $log->jenis == 'masuk' ? '+' : '-' }}{{ number_format($log->jumlah, 2) }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $log->catatan ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-600">{{ $log->user->name ?? 'System' }}</p>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- No Results Message -->
        <div id="noResultsMessage" class="p-8 text-center" style="display: none;">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-600 text-lg">Tidak ada riwayat yang cocok</p>
            <p class="text-gray-500 text-sm mt-1">Coba ubah kriteria pencarian</p>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-6 py-4 border-t border-gray-200" id="paginationContainer">
            {{ $logs->links() }}
        </div>
        @else
            <div class="p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-600 text-lg">Belum ada riwayat perubahan inventory</p>
            </div>
        @endif
    </div>

    <script>
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            filterLogs();
        });

        document.getElementById('jenisFilter')?.addEventListener('change', function() {
            filterLogs();
        });

        function filterLogs() {
            const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const jenisFilter = document.getElementById('jenisFilter')?.value || '';
            const rows = document.querySelectorAll('.log-row');
            let visibleRows = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const jenis = row.querySelector('span')?.textContent.toLowerCase() || '';
                
                const matchesSearch = text.includes(searchTerm);
                const matchesJenis = !jenisFilter || jenis.includes(jenisFilter.toLowerCase());

                if (matchesSearch && matchesJenis) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show/hide no results message
            const noResultsMessage = document.getElementById('noResultsMessage');
            const paginationContainer = document.getElementById('paginationContainer');
            
            if (visibleRows === 0 && rows.length > 0) {
                noResultsMessage.style.display = 'block';
                paginationContainer.style.display = 'none';
            } else {
                noResultsMessage.style.display = 'none';
                paginationContainer.style.display = 'block';
            }
        }

        function resetSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('jenisFilter').value = '';
            document.querySelectorAll('.log-row').forEach(row => {
                row.style.display = '';
            });
            document.getElementById('noResultsMessage').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'block';
        }
    </script>
@endsection
