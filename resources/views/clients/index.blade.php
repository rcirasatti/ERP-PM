@extends('layouts.app')

@section('title', 'Client Management')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Client Management</h1>
            <p class="text-gray-600 mt-2">Kelola data client perusahaan</p>
        </div>
        <a href="{{ route('client.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Client</span>
        </a>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Total Client</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $clients->count() }}</p>
            <p class="text-xs text-gray-600 mt-1">Terdaftar di sistem</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Client Aktif</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $clients->count() }}</p>
            <p class="text-xs text-green-600 mt-1">Siap berkerjasama</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm">Data Terbaru</p>
            <p class="text-sm font-medium text-gray-900 mt-2">
                @if ($clients->count() > 0)
                    {{ $clients->first()->nama }}
                @else
                    Belum ada data
                @endif
            </p>
            <p class="text-xs text-gray-600 mt-1">Ditambahkan terakhir</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <input type="text" id="searchInput" placeholder="Cari client by nama atau email..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button onclick="resetSearch()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Reset
            </button>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if ($clients->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Telepon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="clientTable">
                    @foreach ($clients as $client)
                        <tr class="hover:bg-gray-50 transition client-row">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $client->nama }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">{{ $client->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">{{ $client->telepon }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($client->alamat, 30) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">{{ $client->kontak }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('client.show', $client->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('client.edit', $client->id) }}" class="text-yellow-600 hover:text-yellow-800 font-medium text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button onclick="showConfirm('Yakin ingin menghapus client ini? Data tidak bisa dipulihkan.', 'Hapus Client', function() { document.getElementById('deleteForm{{ $client->id }}').submit(); })" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    <form id="deleteForm{{ $client->id }}" action="{{ route('client.destroy', $client->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- No search results message -->
            <div id="noResultsMessage" class="hidden p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Data tidak tersedia</h3>
                <p class="text-gray-600">Tidak ada client yang sesuai dengan pencarian Anda.</p>
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-600 text-lg">Belum ada data client</p>
                <p class="text-gray-500 text-sm mt-1">Klik tombol "Tambah Client" untuk menambahkan client baru</p>
            </div>
        @endif
    </div>

    <script>
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.client-row');
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
            document.querySelectorAll('.client-row').forEach(row => {
                row.style.display = '';
            });
            document.getElementById('noResultsMessage').style.display = 'none';
        }
    </script>
@endsection
