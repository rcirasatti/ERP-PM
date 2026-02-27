@extends('layouts.app')

@section('title', 'Buat Penawaran')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-gray-600 mb-4">
            <a href="{{ route('penawaran.index') }}" class="hover:text-blue-600">Penawaran</a>
            <span>/</span>
            <span class="text-gray-900">Buat Penawaran</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Buat Penawaran Baru</h1>
                <p class="text-gray-600 text-sm mt-1">Masukkan data penawaran secara manual</p>
            </div>
            <a href="{{ route('penawaran.create-boq') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium text-sm flex items-center space-x-2" title="Atau buat menggunakan file Excel BoQ">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Buat via Upload BoQ</span>
            </a>
        </div>
    </div>

    <!-- Info: Alternative BoQ Method -->
    <div class="mb-8 bg-purple-50 border border-purple-200 rounded-lg p-4 flex items-start">
        <svg class="w-5 h-5 text-purple-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 100 2 1 1 0 000-2zM8 7a1 1 0 100 2 1 1 0 000-2zm1 5a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
        </svg>
        <div>
            <h3 class="font-medium text-purple-900 mb-1">💡 Tips: Buat dengan Upload Excel BoQ</h3>
            <p class="text-sm text-purple-800 mb-2">Lebih cepat? Upload file Excel dengan daftar material untuk membuat penawaran otomatis dengan AI Cost Overrun Analysis.</p>
            <a href="{{ route('penawaran.create-boq') }}" class="text-sm font-medium text-purple-700 hover:text-purple-900 underline">
                Pergi ke form upload BoQ →
            </a>
        </div>
    </div>

    <!-- Form Section -->
    <form action="{{ route('penawaran.store') }}" method="POST" id="penawaranForm">
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
                            <div class="relative">
                                <input type="text" id="client_search" placeholder="Cari client..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <div id="client_dropdown" class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto z-10">
                                    @foreach ($clients as $client)
                                        <div onclick="selectClient({{ $client->id }}, '{{ $client->nama }} ({{ $client->kontak }})')" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm border-b last:border-b-0" data-client-id="{{ $client->id }}">
                                            <div class="font-medium text-gray-900">{{ $client->nama }}</div>
                                            <div class="text-xs text-gray-600">{{ $client->kontak }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <input type="hidden" id="client_id" name="client_id" required>
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

                    <div class="space-y-2 pb-4 border-b border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">PPN 11%:</span>
                            <span class="font-semibold text-gray-900" id="ppnTotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-lg font-bold text-gray-900">Total Tagihan:</span>
                            <span class="text-lg font-bold text-green-600" id="grandTotalWithPpn">Rp 0</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button type="button" onclick="analyzeManualPenawaran()" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Analisis dengan AI DSS
                        </button>
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Simpan Penawaran
                        </button>
                        <a href="{{ route('penawaran.index') }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('components.form-validation')

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
                    <div class="relative">
                        <input type="text" class="material-search w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cari material...">
                        <div class="material-dropdown absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto z-10">
                            @foreach ($materials as $material)
                                @php
                                    $stok = $material->inventory?->stok ?? 0;
                                    $isBarang = $material->type === 'BARANG';
                                    $hasStok = $stok > 0 || !$isBarang; // Jasa/Tol/Lainnya tidak perlu stok
                                    $stokDisplay = $isBarang ? '[Stok: ' . number_format($stok, 2) . ']' : '[' . $material->type . ']';
                                @endphp
                                <div onclick="selectMaterial(this, {{ $material->id }}, '{{ $material->nama }}', {{ $material->harga }}, '{{ $material->satuan }}')" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm border-b last:border-b-0 material-option {{ !$hasStok ? 'opacity-50 cursor-not-allowed' : '' }}" data-material-id="{{ $material->id }}" data-nama="{{ $material->nama }}" data-price="{{ $material->harga }}" data-type="{{ $material->type }}" {{ !$hasStok ? 'onclick="return false;"' : '' }}>
                                    <div class="font-medium text-gray-900">{{ $material->nama }}</div>
                                    <div class="text-xs text-gray-600">{{ $material->satuan }} - Rp {{ number_format($material->harga, 0, ',', '.') }} {{ $stokDisplay }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="items[0][material_id]" required class="material-id-input">
                    <p class="text-xs text-gray-500 mt-1">Hanya Barang dengan stok > 0 dan item non-Barang (Jasa, Tol, Lainnya) yang dapat dipilih</p>
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
            
            // Setup material search for new item
            const newRow = container.querySelector('.item-row:last-child');
            setupMaterialSearch(newRow);
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

                // Total biaya (harga asli * jumlah)
                const biayaItem = hargaAsli * jumlah;
                
                // Margin value = biaya * margin%
                const marginValue = biayaItem * margin / 100;
                
                // Harga jual per unit = harga asli + (harga asli * margin%)
                const hargaJual = hargaAsli + (hargaAsli * margin / 100);
                
                // Subtotal = harga jual * jumlah
                const subtotal = jumlah * hargaJual;

                // Update display
                row.querySelector('.harga-jual-display').textContent = 'Rp ' + number_format(hargaJual, 0, ',', '.');
                row.querySelector('.subtotal-display').textContent = 'Rp ' + number_format(subtotal, 0, ',', '.');

                totalBiaya += biayaItem;
                totalMargin += marginValue;
                totalItems++;
            });

            const grandTotal = totalBiaya + totalMargin;
            const ppn = grandTotal * 0.11;
            const grandTotalWithPpn = grandTotal * 1.11;

            document.getElementById('totalItems').textContent = totalItems;
            document.getElementById('totalBiaya').textContent = 'Rp ' + number_format(totalBiaya, 0, ',', '.');
            document.getElementById('totalMargin').textContent = 'Rp ' + number_format(totalMargin, 0, ',', '.');
            document.getElementById('grandTotal').textContent = 'Rp ' + number_format(grandTotal, 0, ',', '.');
            document.getElementById('ppnTotal').textContent = 'Rp ' + number_format(ppn, 0, ',', '.');
            document.getElementById('grandTotalWithPpn').textContent = 'Rp ' + number_format(grandTotalWithPpn, 0, ',', '.');
        }

        function number_format(num, decimals, dec_point, thousands_sep) {
            let parts = num.toFixed(decimals).split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
            return parts.join(dec_point);
        }

        // Client Search Functionality
        document.getElementById('client_search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const dropdown = document.getElementById('client_dropdown');
            const items = dropdown.querySelectorAll('div[data-client-id]');
            
            if (searchTerm.length > 0) {
                dropdown.classList.remove('hidden');
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.classList.toggle('hidden', !text.includes(searchTerm));
                });
            } else {
                dropdown.classList.add('hidden');
            }
        });

        document.getElementById('client_search').addEventListener('focus', function() {
            document.getElementById('client_dropdown').classList.remove('hidden');
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#client_search') && !e.target.closest('#client_dropdown')) {
                document.getElementById('client_dropdown').classList.add('hidden');
            }
        });

        function selectClient(clientId, clientName) {
            document.getElementById('client_search').value = clientName;
            document.getElementById('client_id').value = clientId;
            document.getElementById('client_dropdown').classList.add('hidden');
        }

        // Material Search Functionality - Setup for new items
        function setupMaterialSearch(row) {
            const searchInput = row.querySelector('.material-search');
            const dropdown = row.querySelector('.material-dropdown');
            const options = dropdown.querySelectorAll('.material-option');

            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                
                if (searchTerm.length > 0) {
                    dropdown.classList.remove('hidden');
                    options.forEach(option => {
                        const text = option.textContent.toLowerCase();
                        const type = option.getAttribute('data-type');
                        const isBarang = type === 'BARANG';
                        const stok = option.getAttribute('data-stok') ?? (isBarang ? 0 : 1);
                        const hasStok = stok > 0 || !isBarang;
                        option.classList.toggle('hidden', !text.includes(searchTerm) || !hasStok);
                    });
                } else {
                    dropdown.classList.add('hidden');
                }
            });

            searchInput.addEventListener('focus', function() {
                if (searchInput.value.length === 0) {
                    dropdown.classList.remove('hidden');
                }
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.material-search') && !e.target.closest('.material-dropdown')) {
                    dropdown.classList.add('hidden');
                }
            });
        }

        function selectMaterial(element, materialId, materialName, price, satuan) {
            const row = element.closest('.item-row');
            const searchInput = row.querySelector('.material-search');
            const dropdown = row.querySelector('.material-dropdown');
            const materialIdInput = row.querySelector('.material-id-input');
            const hargaAsliInput = row.querySelector('.harga-asli-input');
            
            searchInput.value = materialName + ' - ' + satuan;
            materialIdInput.value = materialId;
            hargaAsliInput.value = price;
            dropdown.classList.add('hidden');
            
            calculateTotals();
        }

        // Add initial item on page load
        document.addEventListener('DOMContentLoaded', function() {
            addItem();
        });

        // ============================================
        // AI DSS ANALYSIS FOR MANUAL PENAWARAN
        // ============================================
        async function analyzeManualPenawaran() {
            // Collect form data
            const clientId = document.getElementById('client_id').value;
            const tanggal = document.getElementById('tanggal').value;
            
            if (!clientId) {
                alert('Pilih client terlebih dahulu');
                return;
            }

            // Collect items
            const items = [];
            document.querySelectorAll('.item-row').forEach((row, index) => {
                const materialId = row.querySelector('.material-id-input').value;
                const jumlah = row.querySelector('.jumlah-input').value;
                const hargaAsli = row.querySelector('.harga-asli-input').value;
                const margin = row.querySelector('.margin-input').value;

                if (materialId && jumlah && hargaAsli) {
                    items.push({
                        material_id: materialId,
                        jumlah: parseInt(jumlah),
                        harga_asli: parseFloat(hargaAsli),
                        persentase_margin: parseFloat(margin) || 0
                    });
                }
            });

            if (items.length === 0) {
                alert('Tambahkan minimal satu item');
                return;
            }

            // Send to API for analysis
            try {
                const response = await fetch('{{ route("penawaran.analyze-manual") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        client_id: parseInt(clientId),
                        tanggal: tanggal,
                        items: items
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Gagal menganalisis penawaran');
                }

                // Show analysis results
                showAnalysisResults(data);

            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        function showAnalysisResults(data) {
            // Create a modal or navigate to analysis page
            // For now, we'll create a modal that shows DSS results similar to BoQ
            const analysisHTML = `
                <div class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-black/50">
                    <div class="bg-white rounded-lg shadow-xl p-8 max-w-lg w-full mx-4 max-h-96 overflow-y-auto">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Hasil Analisis AI DSS</h2>
                        
                        <div class="space-y-4">
                            <!-- Risk Level -->
                            <div class="p-4 ${getRiskBgColor(data.risk_level)} rounded-lg">
                                <label class="text-sm font-medium text-gray-600 block mb-2">Tingkat Risiko</label>
                                <div class="flex items-center gap-2">
                                    <span class="${getRiskBadgeClass(data.risk_level)} px-4 py-2 rounded-full text-white font-semibold">
                                        ${getRiskEmoji(data.risk_level)} ${data.risk_level}
                                    </span>
                                </div>
                            </div>

                            <!-- Recommendation -->
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <label class="text-sm font-medium text-blue-900 block mb-2">Rekomendasi AI</label>
                                <p class="text-sm text-blue-800">${data.recommendation || 'Silakan tinjau faktor risiko di atas.'}</p>
                            </div>

                            <!-- Predictions -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-gray-50 rounded">
                                    <p class="text-xs text-gray-600 mb-1">Prediksi LR</p>
                                    <p class="text-sm font-bold text-gray-900">Rp ${data.predictions?.lr ? data.predictions.lr.toLocaleString('id-ID') : 0}</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded">
                                    <p class="text-xs text-gray-600 mb-1">Prediksi MA</p>
                                    <p class="text-sm font-bold text-gray-900">Rp ${data.predictions?.ma ? data.predictions.ma.toLocaleString('id-ID') : 0}</p>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-3 pt-4 border-t border-gray-200">
                                <button onclick="closeAnalysisModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300">
                                    Kembali
                                </button>
                                <button type="submit" form="penawaranForm" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', analysisHTML);
        }

        function closeAnalysisModal() {
            document.querySelector('[style*="fixed"][style*="z-50"]')?.remove();
        }

        function getRiskBgColor(risk) {
            if (risk === 'Tinggi') return 'bg-red-50 border border-red-200';
            if (risk === 'Sedang') return 'bg-yellow-50 border border-yellow-200';
            return 'bg-green-50 border border-green-200';
        }

        function getRiskBadgeClass(risk) {
            if (risk === 'Tinggi') return 'bg-red-600';
            if (risk === 'Sedang') return 'bg-yellow-600';
            return 'bg-green-600';
        }

        function getRiskEmoji(risk) {
            if (risk === 'Tinggi') return '🔴';
            if (risk === 'Sedang') return '⚠️';
            return '✓';
        }
