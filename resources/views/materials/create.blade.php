@extends('layouts.app')

@section('title', 'Tambah Material')

@section('content')
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('material.index') }}" class="hover:text-blue-600">Material</a>
            <span>/</span>
            <span>Tambah</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Tambah Material Baru</h2>

        <form action="{{ route('material.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Material -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Material</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('nama') border-red-500 @enderror"
                    placeholder="Contoh: Batu Bata">
                @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Supplier -->
            <div>
                <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                <select name="supplier_id" id="supplier_id" required
                    class="searchable-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('supplier_id') border-red-500 @enderror">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->nama }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Satuan -->
            <div>
                <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                <input type="text" name="satuan" id="satuan" value="{{ old('satuan') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('satuan') border-red-500 @enderror"
                    placeholder="Contoh: Pcs, Box, Meter">
                @error('satuan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga -->
            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                <input type="number" name="harga" id="harga" value="{{ old('harga') }}" step="0.01" min="0" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('harga') border-red-500 @enderror"
                    placeholder="Contoh: 50000">
                @error('harga')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Simpan</span>
                </button>
                <a href="{{ route('material.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
