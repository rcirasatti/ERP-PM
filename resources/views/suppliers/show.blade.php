@extends('layouts.app')

@section('title', 'Detail Supplier')

@section('content')
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('supplier.index') }}" class="hover:text-blue-600">Supplier</a>
                <span>/</span>
                <span>{{ $supplier->nama }}</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('supplier.edit', $supplier->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                </a>
                <a href="{{ route('supplier.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Detail Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Informasi Supplier</h2>

                <div class="space-y-6">
                    <!-- Nama -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Nama Supplier</p>
                        <p class="text-xl font-semibold text-gray-900">{{ $supplier->nama }}</p>
                    </div>

                    <!-- Email -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Email</p>
                        <p class="text-lg text-gray-900">
                            <a href="mailto:{{ $supplier->email }}" class="text-blue-600 hover:text-blue-800">{{ $supplier->email }}</a>
                        </p>
                    </div>

                    <!-- Telepon -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Telepon</p>
                        <p class="text-lg text-gray-900">
                            <a href="tel:{{ $supplier->telepon }}" class="text-blue-600 hover:text-blue-800">{{ $supplier->telepon }}</a>
                        </p>
                    </div>

                    <!-- Kontak -->
                    <div class="pb-6 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Kontak Nama</p>
                        <p class="text-lg text-gray-900">{{ $supplier->kontak }}</p>
                    </div>

                    <!-- Alamat -->
                    <div class="pb-6">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2">Alamat</p>
                        <p class="text-lg text-gray-900 whitespace-pre-wrap">{{ $supplier->alamat }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Actions -->
        <div class="lg:col-span-1">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4">Status</h3>
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-green-700 font-medium">Aktif</span>
                </div>
            </div>

            <!-- Metadata Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Metadata</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-1">ID</p>
                        <p class="text-gray-900">{{ $supplier->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-1">Dibuat</p>
                        <p class="text-gray-900">{{ $supplier->created_at ? $supplier->created_at->format('d M Y H:i') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Delete Button -->
            <div class="mt-6">
                <button onclick="showConfirm('Yakin ingin menghapus supplier ini? Data tidak bisa dipulihkan.', 'Hapus Supplier', function() { document.getElementById('deleteForm').submit(); })" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span>Hapus Supplier</span>
                </button>
                <form id="deleteForm" action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
@endsection
