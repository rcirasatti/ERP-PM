@extends('layouts.app')

@section('title', 'Detail Budget - ' . $budget->proyek->nama)

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center space-x-2 mb-2">
                <a href="{{ route('finance.budget') }}" class="text-blue-600 hover:text-blue-800">Budget Proyek</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-900">{{ $budget->proyek->nama }}</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $budget->proyek->nama }}</h1>
            <p class="text-gray-600 mt-2">{{ $budget->proyek->client->nama }}</p>
        </div>
        <a href="{{ route('finance.budget') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Budget Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Budget Rencana</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">Rp {{ number_format($budget->jumlah_rencana, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-600 mt-1">Anggaran terencana</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <p class="text-gray-600 text-sm">Realisasi</p>
            <p class="text-3xl font-bold text-red-600 mt-2">Rp {{ number_format($budget->jumlah_realisasi, 0, ',', '.') }}</p>
            <p class="text-xs text-red-600 mt-1">Total pengeluaran</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Sisa Budget</p>
            <p class="text-3xl font-bold text-green-600 mt-2">Rp {{ number_format($budget->sisa_budget, 0, ',', '.') }}</p>
            <p class="text-xs text-green-600 mt-1">Sisa tersedia</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 {{ $budget->persentase_penggunaan < 70 ? 'border-green-500' : ($budget->persentase_penggunaan < 90 ? 'border-yellow-500' : 'border-red-500') }}">
            <p class="text-gray-600 text-sm">Penggunaan</p>
            <p class="text-3xl font-bold {{ $budget->persentase_penggunaan < 70 ? 'text-green-600' : ($budget->persentase_penggunaan < 90 ? 'text-yellow-600' : 'text-red-600') }} mt-2">{{ number_format($budget->persentase_penggunaan, 1) }}%</p>
            <p class="text-xs {{ $budget->persentase_penggunaan < 70 ? 'text-green-600' : ($budget->persentase_penggunaan < 90 ? 'text-yellow-600' : 'text-red-600') }} mt-1">
                @if($budget->getStatusBudget() == 'aman')
                    Status Aman
                @elseif($budget->getStatusBudget() == 'peringatan')
                    Status Peringatan
                @else
                    Status Bahaya
                @endif
            </p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900">Progress Penggunaan Budget</h3>
            <span class="text-sm font-semibold text-gray-900">{{ number_format($budget->persentase_penggunaan, 1) }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-4">
            <div class="h-4 rounded-full {{ $budget->persentase_penggunaan < 70 ? 'bg-green-500' : ($budget->persentase_penggunaan < 90 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                 style="width: {{ min($budget->persentase_penggunaan, 100) }}%"></div>
        </div>
    </div>

    <!-- Pengeluaran Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Riwayat Pengeluaran</h3>
            <a href="{{ route('pengeluaran.create', ['proyek_id' => $budget->proyek_id]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Pengeluaran</span>
            </a>
        </div>

        @if($pengeluarans->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full min-w-max">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Dibuat Oleh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pengeluarans as $pengeluaran)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $pengeluaran->tanggal->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $pengeluaran->kategori)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 line-clamp-2">{{ $pengeluaran->deskripsi }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-red-600">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $pengeluaran->creator->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        <a href="{{ route('pengeluaran.edit', $pengeluaran->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button onclick="deletePengeluaran({{ $pengeluaran->id }})" class="text-red-600 hover:text-red-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-600 text-lg font-medium">Belum ada pengeluaran</p>
                <p class="text-gray-500 text-sm mt-2">Klik tombol "Tambah Pengeluaran" untuk membuat pengeluaran baru</p>
            </div>
        @endif
    </div>

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function deletePengeluaran(id) {
            showConfirm(
                'Pengeluaran yang dihapus tidak dapat dikembalikan. Budget proyek akan terupdate otomatis.',
                'Hapus Pengeluaran?',
                function() {
                    document.getElementById('delete-form').action = `/pengeluaran/${id}`;
                    document.getElementById('delete-form').submit();
                }
            );
        }
    </script>
@endsection
