@extends('layouts.app')

@section('title', 'Buat Penawaran')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-gray-600 mb-4">
            <a href="{{ route('quotations.index') }}" class="hover:text-blue-600">Penawaran</a>
            <span>/</span>
            <span class="text-gray-900">Buat Penawaran</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Buat Penawaran Baru</h1>
    </div>

    <!-- Form Section -->
    <form action="{{ route('quotations.store') }}" method="POST" id="penawaranForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- No. Penawaran -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penawaran</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="no_penawaran" class="block text-sm font-medium text-gray-700 mb-2">No. Penawaran</label>
                            <input type="text" id="no_penawaran" name="no_penawaran" value="{{ $noPenawaran }}" readonly class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                        </div>

                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                            <select id="client_id" name="client_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Client --</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->nama }} ({{ $client->kontak }})</option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Penawaran *</label>
                            <input type="date" id="tanggal" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('tanggal')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="draft">Draft</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Items Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Item Penawaran</h2>
                        <button type="button" onclick="addItem()" class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                            + Tambah Item
                        </button>
                    </div>

                    <div id="itemsContainer" class="space-y-4">
                        <!-- Item template will be added here -->
                    </div>

                    @error('items')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Column - Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan</h2>
                    
                    <div class="space-y-3 pb-4 border-b border-gray-200">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Item:</span>
                            <span class="font-semibold text-gray-900" id="totalItems">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Biaya:</span>
                            <span class="font-semibold text-gray-900" id="totalBiaya">Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Margin:</span>
                            <span class="font-semibold text-gray-900" id="totalMargin">Rp 0</span>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4 mb-6">
                        <span class="text-lg font-bold text-gray-900">Grand Total:</span>
                        <span class="text-lg font-bold text-blue-600" id="grandTotal">Rp 0</span>
                    </div>

                    <div class="space-y-3">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Simpan Penawaran
                        </button>
                        <a href="{{ route('quotations.index') }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Item Template (Hidden) -->
    <template id="itemTemplate">
        <div class="item-row border border-gray-200 rounded-lg p-4 bg-gray-50">
            <div class="flex items-start justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Item <span class="item-number">1</span></h3>
                <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 font-medium text-sm">
                    Hapus
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Material *</label>
                    <select name="items[0][material_id]" required onchange="updateHargaAsli(this)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 material-select">
                        <option value="">-- Pilih Material --</option>
                        @foreach ($materials as $material)
                            @php
                                $stok = $material->inventory?->stok ?? 0;
                                $hasStok = $stok > 0;
                            @endphp
                            <option value="{{ $material->id }}" data-nama="{{ $material->nama }}" {{ !$hasStok ? 'disabled' : '' }}>
                                {{ $material->nama }} - {{ $material->satuan }} {{ !$hasStok ? '[Stok: 0 - Tidak Tersedia]' : '[Stok: ' . number_format($stok, 2) . ']' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Material dengan stok 0 tidak bisa dipilih</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah *</label>
                    <input type="number" name="items[0][jumlah]" step="1" min="1" required onchange="calculateTotals()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 jumlah-input">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Asli (dari Material) *</label>
                    <input type="number" name="items[0][harga_asli]" step="0.01" min="0" required readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none harga-asli-input">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Margin (%) *</label>
                    <input type="number" name="items[0][persentase_margin]" step="0.01" min="0" max="100" value="0" required onchange="calculateTotals()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 margin-input">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jual (Otomatis)</label>
                    <div class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium harga-jual-display">
                        Rp 0
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subtotal</label>
                    <div class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium subtotal-display">
                        Rp 0
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        let itemCount = 0;

        function addItem() {
            const container = document.getElementById('itemsContainer');
            const template = document.getElementById('itemTemplate');
            const clone = template.content.cloneNode(true);
            
            // Update item number and input names
            const itemNumber = ++itemCount;
            clone.querySelector('.item-number').textContent = itemNumber;
            
            // Update all input names with correct index
            clone.querySelectorAll('input, select').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\[0\]/, `[${itemNumber - 1}]`));
                }
            });

            container.appendChild(clone);
        }

        function removeItem(btn) {
            btn.closest('.item-row').remove();
            calculateTotals();
        }

        function updateHargaAsli(selectElement) {
            const row = selectElement.closest('.item-row');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const hargaAsli = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            
            const hargaAsliInput = row.querySelector('.harga-asli-input');
            hargaAsliInput.value = hargaAsli;
            
            calculateTotals();
        }

        function calculateTotals() {
            let totalBiaya = 0;
            let totalMargin = 0;
            let totalItems = 0;

            document.querySelectorAll('.item-row').forEach(row => {
                const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
                const hargaAsli = parseFloat(row.querySelector('.harga-asli-input').value) || 0;
                const margin = parseFloat(row.querySelector('.margin-input').value) || 0;

                // Kalkulasi harga jual = harga asli + (harga asli * margin%)
                const hargaJual = hargaAsli + (hargaAsli * margin / 100);
                
                // Subtotal = harga jual * jumlah
                const subtotal = jumlah * hargaJual;
                
                // Margin value = subtotal - (harga asli * jumlah)
                const marginValue = subtotal - (hargaAsli * jumlah);

                // Update display
                row.querySelector('.harga-jual-display').textContent = 'Rp ' + number_format(hargaJual, 0, ',', '.');
                row.querySelector('.subtotal-display').textContent = 'Rp ' + number_format(subtotal, 0, ',', '.');

                totalBiaya += subtotal;
                totalMargin += marginValue;
                totalItems++;
            });

            document.getElementById('totalItems').textContent = totalItems;
            document.getElementById('totalBiaya').textContent = 'Rp ' + number_format(totalBiaya, 0, ',', '.');
            document.getElementById('totalMargin').textContent = 'Rp ' + number_format(totalMargin, 0, ',', '.');
            document.getElementById('grandTotal').textContent = 'Rp ' + number_format(totalBiaya, 0, ',', '.');
        }

        function number_format(num, decimals, dec_point, thousands_sep) {
            let parts = num.toFixed(decimals).split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
            return parts.join(dec_point);
        }

        // Add initial item on page load
        document.addEventListener('DOMContentLoaded', function() {
            addItem();
        });
    </script>
@endsection
