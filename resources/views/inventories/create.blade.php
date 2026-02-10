@extends('layouts.app')

@section('title', 'Tambah Inventory')

@section('content')
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('inventory.index') }}" class="hover:text-blue-600">Inventory</a>
            <span>/</span>
            <span>Tambah Inventory</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Form Tambah Inventory Baru</h2>

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <h3 class="font-medium text-red-800 mb-2">Terjadi Kesalahan:</h3>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-700">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('inventory.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Material -->
                    <div>
                        <label for="material_id" class="block text-sm font-medium text-gray-700 mb-2">Material *</label>
                        <select id="material_id" name="material_id" class="searchable-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('material_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih Material --</option>
                            @foreach ($materials as $material)
                                @if ($material->needsInventoryTracking())
                                    <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                        {{ $material->nama }} ({{ $material->satuan }}) - Rp {{ number_format($material->harga, 0, ',', '.') }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('material_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Hanya material bertipe Barang yang menampilkan tracking inventory</p>
                    </div>

                    <!-- Stok -->
                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok *</label>
                        <input type="number" id="stok" name="stok" value="{{ old('stok') }}" placeholder="Masukkan jumlah stok" step="1" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stok') border-red-500 @enderror" required>
                        @error('stok')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-3 pt-6">
                        <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Simpan Inventory
                        </button>
                        <a href="{{ route('inventory.index') }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium text-center">
                            Batal
                        </a>
                    </div>
                </form>
                @include('components.form-validation')
            </div>
        </div>

        <!-- Info Section -->
        <div class="lg:col-span-1">
            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                <h3 class="font-semibold text-blue-900 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Panduan Pengisian</span>
                </h3>
                <ul class="space-y-3 text-sm text-blue-800">
                    <li class="flex space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Material:</strong> Pilih material dari list yang tersedia</span>
                    </li>
                    <li class="flex space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Stok:</strong> Masukkan jumlah stok dalam satuan yang sesuai dengan material</span>
                    </li>
                    <li class="flex space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Format:</strong> Stok harus berupa bilangan bulat (tidak boleh desimal)</span>
                    </li>
                </ul>
                
                <div class="mt-6 pt-6 border-t border-blue-200">
                    <p class="text-sm text-blue-800 mb-3"><strong>Material tidak ada di list?</strong></p>
                    <a href="{{ route('material.create') }}" class="inline-block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                        Buat Material Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
