@extends('layouts.app')
@section('title', 'Budget Proyek')
@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Budget Proyek</h1>
        <p class="text-gray-600 mt-2">Kelola dan monitor budget untuk setiap proyek</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <p class="text-gray-600 text-sm">Total Budget Rencana</p>
        <p class="text-3xl font-bold text-blue-600 mt-2">Rp {{ number_format($budgets->sum('jumlah_rencana'), 0, ',', '.') }}</p>
        <p class="text-xs text-gray-600 mt-1">Total rencana anggaran</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
        <p class="text-gray-600 text-sm">Total Realisasi</p>
        <p class="text-3xl font-bold text-red-600 mt-2">Rp {{ number_format($budgets->sum('jumlah_realisasi'), 0, ',', '.') }}</p>
        <p class="text-xs text-red-600 mt-1">Total pengeluaran</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <p class="text-gray-600 text-sm">Total Sisa Budget</p>
        <p class="text-3xl font-bold text-green-600 mt-2">Rp {{ number_format($budgets->sum('sisa_budget'), 0, ',', '.') }}</p>
        <p class="text-xs text-green-600 mt-1">Sisa budget tersedia</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
        <p class="text-gray-600 text-sm">Rata-rata Penggunaan</p>
        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ number_format($budgets->average('persentase_penggunaan'), 1) }}%</p>
        <p class="text-xs text-yellow-600 mt-1">Rata-rata utilisasi</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <input type="text" id="searchInput" placeholder="Cari budget atau client..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div id="noResults" class="mt-2 text-sm text-gray-500 hidden">Tidak ada hasil pencarian</div>
        </div>
        <a href="{{ route('finance.budget') }}" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Reset</a>
    </div>
</div>

@if($budgets->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($budgets as $budget)
    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-5 border border-gray-100">
        <div class="flex justify-between items-start mb-3">
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 text-sm line-clamp-2">{{ $budget->proyek->nama }}</h3>
                <p class="text-xs text-gray-600 mt-1">{{ $budget->proyek->client->nama ?? 'N/A' }}</p>
            </div>
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2 {{ $budget->getStatusColor() }}">
                @if($budget->getStatusBudget() == 'aman')
                Aman
                @elseif($budget->getStatusBudget() == 'peringatan')
                Peringatan
                @else
                Bahaya
                @endif
            </span>
        </div>

        <div class="flex items-center text-xs text-gray-500 mb-3">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Status: <strong class="ml-1">{{ $budget->proyek->getStatusLabel() }}</strong>
        </div>

        <div class="space-y-2 mb-3 text-xs">
            <div class="flex justify-between">
                <span class="text-gray-600">Rencana:</span>
                <span class="font-semibold text-gray-900">Rp {{ number_format($budget->jumlah_rencana / 1000000, 1) }}jt</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Realisasi:</span>
                <span class="font-semibold text-red-600">Rp {{ number_format($budget->jumlah_realisasi / 1000000, 1) }}jt</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Sisa:</span>
                <span class="font-semibold text-green-600">Rp {{ number_format($budget->sisa_budget / 1000000, 1) }}jt</span>
            </div>
        </div>

        <div class="mb-3">
            <div class="flex justify-between items-center mb-1">
                <span class="text-xs font-medium text-gray-700">Penggunaan</span>
                <span class="text-xs font-bold text-gray-900">{{ number_format($budget->persentase_penggunaan, 1) }}%</span>
            </div>
            <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-2 rounded-full {{ $budget->persentase_penggunaan < 70 ? 'bg-green-500' : ($budget->persentase_penggunaan < 90 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ min($budget->persentase_penggunaan, 100) }}%"></div>
            </div>
        </div>

        <div class="flex gap-2 pt-2 border-t border-gray-100">
            <a href="{{ route('finance.budget.show', $budget->id) }}" class="flex-1 text-center px-2 py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-50 rounded transition" title="Lihat Detail">
                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </a>
            <a href="{{ route('pengeluaran.create', ['proyek_id' => $budget->proyek_id]) }}" class="flex-1 text-center px-2 py-1.5 text-xs font-medium text-green-600 hover:bg-green-50 rounded transition" title="Tambah Pengeluaran">
                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </a>
        </div>
    </div>
    @endforeach
</div>

@if($budgets->hasPages())
<div class="mt-8">
    {{ $budgets->appends(request()->query())->links() }}
</div>
@endif
@else
<div class="bg-white rounded-lg shadow-md p-8 text-center">
    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
    </svg>
    <p class="text-gray-600 text-lg font-medium">Belum ada hasil pencarian</p>
    <p class="text-gray-500 text-sm mt-2">Coba cari dengan kata kunci yang berbeda</p>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    
    // Get current search query if exists
    const urlParams = new URLSearchParams(window.location.search);
    const currentQuery = urlParams.get('q') || '';
    searchInput.value = currentQuery;
    
    // Live search with debounce
    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        searchTimeout = setTimeout(() => {
            // Build the new URL with query parameter
            const baseUrl = window.location.pathname;
            if (query.length > 0) {
                window.location.href = `${baseUrl}?q=${encodeURIComponent(query)}`;
            } else {
                window.location.href = baseUrl;
            }
        }, 300); // 300ms debounce to avoid too many requests
    });
});
</script>
@endsection
