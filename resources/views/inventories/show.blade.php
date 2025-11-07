@extends('layouts.app')

@section('title', 'Detail Inventory')

@section('content')
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('inventory.index') }}" class="hover:text-blue-600">Inventory</a>
                <span>/</span>
                <span>{{ $inventory->material->nama ?? 'N/A' }}</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('inventory.edit', $inventory->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                </a>
                <a href="{{ route('inventory.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Detail Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Informasi Inventory</h2>

                <div class="space-y-6">
                    <!-- Material Info -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Material</p>
                        <p class="text-xl font-semibold text-gray-900">{{ $inventory->material->nama ?? 'N/A' }}</p>
                    </div>

                    <!-- Supplier -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Supplier</p>
                        <p class="text-lg text-gray-900">
                            {{ $inventory->material->supplier->nama ?? 'N/A' }}
                        </p>
                    </div>

                    <!-- Satuan -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Satuan</p>
                        <p class="text-lg text-gray-900">{{ $inventory->material->satuan ?? 'N/A' }}</p>
                    </div>

                    <!-- Harga -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Harga per Unit</p>
                        <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($inventory->material->harga, 0, ',', '.') }}</p>
                    </div>

                    <!-- Stok -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Stok Saat Ini</p>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($inventory->stok, 2) }}</p>
                    </div>

                    <!-- Total Nilai -->
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Total Nilai Inventory</p>
                        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($inventory->stok * $inventory->material->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Actions -->
        <div class="lg:col-span-1">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4">Status Stok</h3>
                <div class="flex items-center space-x-3">
                    @if ($inventory->stok > 0)
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-green-700 font-medium">Tersedia</span>
                    @else
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-red-700 font-medium">Habis</span>
                    @endif
                </div>
            </div>

            <!-- Metadata Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Metadata</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-1">ID</p>
                        <p class="text-gray-900">{{ $inventory->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-1">Terakhir Diupdate</p>
                        <p class="text-gray-900">{{ $inventory->updated_at ? $inventory->updated_at->format('d M Y H:i') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Delete Button -->
            <div class="mt-6">
                <button onclick="showConfirm('Yakin ingin menghapus inventory ini? Data tidak bisa dipulihkan.', 'Hapus Inventory', function() { document.getElementById('deleteForm').submit(); })" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span>Hapus Inventory</span>
                </button>
                <form id="deleteForm" action="{{ route('inventory.destroy', $inventory->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
@endsection
