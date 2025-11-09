@extends('layouts.app')

@section('title', 'Penawaran')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Penawaran</h1>
            <p class="text-gray-600 mt-2">Kelola semua penawaran kepada client</p>
        </div>
        <a href="{{ route('quotations.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Buat Penawaran</span>
        </a>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Total Penawaran</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPenawaran }}</p>
            <p class="text-xs text-gray-600 mt-1">Semua penawaran</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Total Nilai</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
            <p class="text-xs text-green-600 mt-1">Total biaya semua penawaran</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <p class="text-gray-600 text-sm">Penawaran Pending</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingPenawaran }}</p>
            <p class="text-xs text-yellow-600 mt-1">Belum ditanggapi</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <input type="text" id="searchInput" placeholder="Cari no. penawaran atau nama client..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
            <button onclick="resetFilters()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Reset
            </button>
        </div>
    </div>

    <!-- Penawaran Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if ($penawaran->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full min-w-max">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">No. Penawaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Total Biaya</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Total Margin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="penawaranTable">
                        @foreach ($penawaran as $item)
                            <tr class="hover:bg-gray-50 transition penawaran-row" data-status="{{ $item->status }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-medium text-blue-600">{{ $item->no_penawaran }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900 whitespace-nowrap">{{ $item->client->nama }}</p>
                                    <p class="text-xs text-gray-600 whitespace-nowrap">{{ $item->client->kontak }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-medium text-gray-900">Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-medium text-gray-900">Rp {{ number_format($item->total_margin, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-600">
                                    {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $item->getStatusColor() }}">
                                    {{ $item->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                <div class="flex gap-2">
                                    <a href="{{ route('quotations.show', $item->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('quotations.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-800 font-medium text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button onclick="showConfirm('Apakah Anda yakin ingin menghapus penawaran ini?', 'Hapus Penawaran', () => deletePenawaran({{ $item->id }}))" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
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
                <p class="text-gray-600">Tidak ada penawaran yang sesuai dengan pencarian Anda.</p>
            </div>
        @else
            <div class="p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-600 text-lg">Belum ada penawaran</p>
                <a href="{{ route('quotations.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Buat Penawaran Pertama
                </a>
            </div>
        @endif
    </div>

    @if (session('success'))
        <script>
            showToast("{{ session('success') }}", 'success');
        </script>
    @endif

    <script>
        function filterTable() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const statusValue = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.penawaran-row');
            let visibleRows = 0;

            rows.forEach(row => {
                // Get all text content from the row for search
                const text = row.textContent.toLowerCase();
                // Get status from data attribute
                const status = row.getAttribute('data-status');

                // Check if matches search
                const matchesSearch = text.includes(searchValue);
                // Check if matches status filter
                const matchesStatus = statusValue === '' || status === statusValue;

                // Show/hide row
                if (matchesSearch && matchesStatus) {
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
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.querySelectorAll('.penawaran-row').forEach(row => {
                row.style.display = '';
            });
            document.getElementById('noResultsMessage').style.display = 'none';
        }

        function deletePenawaran(id) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("quotations.destroy", ":id") }}'.replace(':id', id);
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }

        // Initialize event listeners
        document.getElementById('searchInput')?.addEventListener('keyup', filterTable);
        document.getElementById('statusFilter')?.addEventListener('change', filterTable);
    </script>
@endsection