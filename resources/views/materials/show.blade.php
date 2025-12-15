@extends('layouts.app')

@section('title', 'Detail Item Penawaran')

@section('content')
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('material.index') }}" class="hover:text-blue-600">Item Penawaran</a>
                <span>/</span>
                <span>{{ $material->deskripsi }}</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('material.edit', $material->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                </a>
                <a href="{{ route('material.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Detail Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Informasi Item Penawaran</h2>

                <div class="space-y-6">
                    <!-- Nama -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Nama Item Penawaran</p>
                        <p class="text-xl font-semibold text-gray-900">{{ $material->nama }}</p>
                    </div>

                    <!-- Kode -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Kode Item</p>
                        <p class="text-lg text-gray-900">{{ $material->kode ?? '-' }}</p>
                    </div>

                    <!-- Tipe Item Penawaran -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Tipe Item Penawaran</p>
                        <span class="inline-block px-3 py-1 
                            @if($material->type === 'BARANG') bg-blue-100 text-blue-800
                            @elseif($material->type === 'JASA') bg-green-100 text-green-800
                            @elseif($material->type === 'TOL') bg-orange-100 text-orange-800
                            @else bg-gray-100 text-gray-800
                            @endif
                            rounded-full text-sm font-medium">
                            {{ $material->type }}
                        </span>
                    </div>

                    <!-- Supplier (Only if not null) -->
                    @if ($material->supplier)
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Supplier</p>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-semibold text-sm">{{ strtoupper(substr($material->supplier->nama, 0, 1)) }}</span>
                            </div>
                            <p class="text-lg text-gray-900">
                                <a href="{{ route('supplier.show', $material->supplier->id) }}" class="text-blue-600 hover:text-blue-800">{{ $material->supplier->nama }}</a>
                            </p>
                        </div>
                    </div>
                    @else
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Supplier</p>
                        <p class="text-lg text-gray-500 italic">Tidak ada supplier (Tipe: {{ $material->type }})</p>
                    </div>
                    @endif

                    <!-- Satuan -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Satuan</p>
                        <p class="text-lg text-gray-900 bg-gray-100 px-3 py-2 rounded inline-block font-medium">{{ $material->satuan }}</p>
                    </div>

                    <!-- Harga -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Harga</p>
                        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($material->harga, 0, ',', '.') }}</p>
                    </div>

                    <!-- Track Inventory -->
                    <div class="pb-6">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Tracking Inventory</p>
                        @if ($material->track_inventory)
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded text-sm font-medium">
                                ✓ Aktif
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded text-sm font-medium">
                                ✗ Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Actions -->
        <div class="lg:col-span-1">
            <!-- Metadata Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4">Metadata</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-1">ID</p>
                        <p class="text-gray-900">{{ $material->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-1">Dibuat</p>
                        <p class="text-gray-900">{{ isset($material->create_at) && $material->create_at ? \Carbon\Carbon::parse($material->create_at)->format('d M Y H:i') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-1">Diupdate</p>
                        <p class="text-gray-900">{{ isset($material->updated_at) && $material->updated_at ? \Carbon\Carbon::parse($material->updated_at)->format('d M Y H:i') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Delete Button -->
            <div class="mt-6">
                <button onclick="showConfirm('Yakin ingin menghapus item penawaran ini? Data tidak bisa dipulihkan.', 'Hapus Item Penawaran', function() { document.getElementById('deleteForm').submit(); })" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span>Hapus Item Penawaran</span>
                </button>
                <form id="deleteForm" action="{{ route('material.destroy', $material->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
@endsection
