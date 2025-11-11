@extends('layouts.app')

@section('title', 'Budget Proyek')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Budget Proyek</h1>
            <p class="text-gray-600 mt-2">Kelola dan monitor budget untuk setiap proyek</p>
        </div>
        <a href="{{ route('finance.dashboard') }}" class="px-6 py-3 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    <!-- Budget List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($budgets->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Proyek</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Budget Rencana</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Realisasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Sisa Budget</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($budgets as $budget)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('proyek.show', $budget->proyek_id) }}" class="font-medium text-blue-600 hover:text-blue-800">
                                    {{ $budget->proyek->nama }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $budget->proyek->getStatusLabel() }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $budget->proyek->client->nama }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($budget->jumlah_rencana, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-red-600">Rp {{ number_format($budget->jumlah_realisasi, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-green-600">Rp {{ number_format($budget->sisa_budget, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-medium text-gray-700 mb-1">{{ number_format($budget->persentase_penggunaan, 1) }}%</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $budget->persentase_penggunaan < 70 ? 'bg-green-500' : ($budget->persentase_penggunaan < 90 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                             style="width: {{ min($budget->persentase_penggunaan, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $budget->getStatusColor() }}">
                                    @if($budget->getStatusBudget() == 'aman')
                                        Aman
                                    @elseif($budget->getStatusBudget() == 'peringatan')
                                        Peringatan
                                    @else
                                        Bahaya
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-500 text-lg font-medium">Belum ada budget proyek</p>
                <p class="text-gray-400 text-sm mt-2">Budget akan otomatis dibuat saat membuat proyek baru</p>
            </div>
        @endif

        <!-- Pagination -->
        @if($budgets->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $budgets->links() }}
            </div>
        @endif
    </div>

    <!-- Legend Section -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Keterangan Status Budget</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aman</span>
                <p class="text-sm text-gray-600">Penggunaan budget < 70%</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Peringatan</span>
                <p class="text-sm text-gray-600">Penggunaan budget 70% - 90%</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Bahaya</span>
                <p class="text-sm text-gray-600">Penggunaan budget > 90%</p>
            </div>
        </div>
    </div>
@endsection
