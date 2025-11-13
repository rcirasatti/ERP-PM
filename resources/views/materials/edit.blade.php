@extends('layouts.app')

@section('title', 'Edit Material')

@section('content')
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('material.index') }}" class="hover:text-blue-600">Material</a>
            <span>/</span>
            <span>Edit</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Material</h2>

        <form action="{{ route('material.update', $material->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Material -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Material</label>
                <input type="text" name="deskripsi" id="deskripsi" value="{{ old('deskripsi', $material->deskripsi) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('deskripsi') border-red-500 @enderror">
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipe Material -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Material</label>
                <select name="type" id="type" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('type') border-red-500 @enderror"
                    onchange="updateSupplierRequirement()">
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" {{ old('type', $material->type) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Supplier (Only for BARANG) -->
            <div id="supplierDiv">
                <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Supplier <span id="supplierRequired" class="text-red-600">*</span>
                </label>
                <select name="supplier_id" id="supplier_id" class="searchable-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('supplier_id') border-red-500 @enderror">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $material->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->nama }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500" id="supplierHint">Supplier diperlukan untuk tipe Barang</p>
            </div>

            <!-- Satuan -->
            <div>
                <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $material->satuan) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('satuan') border-red-500 @enderror">
                @error('satuan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga -->
            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                <input type="number" name="harga" id="harga" value="{{ old('harga', $material->harga) }}" step="0.01" min="0" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('harga') border-red-500 @enderror">
                @error('harga')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Track Inventory (Only for BARANG) -->
            <div id="trackInventoryDiv" class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="hidden" name="track_inventory" value="0">
                    <input type="checkbox" name="track_inventory" value="1" id="track_inventory" 
                        {{ old('track_inventory', $material->track_inventory) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">Tracking Inventory</span>
                </label>
                <p class="mt-2 text-xs text-gray-600">Centang untuk melacak stok material ini</p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
                <a href="{{ route('material.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                    Batal
                </a>
            </div>
        </form>
        @include('components.form-validation')

        <script>
            function updateSupplierRequirement() {
                const typeSelect = document.getElementById('type');
                const supplierDiv = document.getElementById('supplierDiv');
                const supplierSelect = document.getElementById('supplier_id');
                const supplierRequired = document.getElementById('supplierRequired');
                const trackInventoryDiv = document.getElementById('trackInventoryDiv');
                const trackInventoryCheckbox = document.getElementById('track_inventory');
                
                const isBarang = typeSelect.value === 'BARANG';
                
                // Toggle supplier requirement
                if (isBarang) {
                    supplierDiv.style.display = 'block';
                    supplierSelect.required = true;
                    supplierRequired.style.display = 'inline';
                    trackInventoryDiv.style.display = 'block';
                } else {
                    supplierDiv.style.display = 'none';
                    supplierSelect.required = false;
                    supplierRequired.style.display = 'none';
                    trackInventoryDiv.style.display = 'none';
                    trackInventoryCheckbox.checked = false;
                    supplierSelect.value = '';
                }
            }
            
            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                updateSupplierRequirement();
            });
        </script>
    </div>
@endsection
