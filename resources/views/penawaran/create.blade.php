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
                            <select id="client_id" name="client_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 searchable-select">
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

                        <div>
                            <label for="wilayah" class="block text-sm font-medium text-gray-700 mb-2">Wilayah *</label>
                            <select id="wilayah" name="wilayah" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 searchable-select">
                                <option value="">-- Pilih Wilayah --</option>
                                @foreach(['Batang', 'Blora', 'Boyolali', 'Brebes', 'Cilacap', 'Demak', 'Karanganyar', 'Kendal', 'Kota Semarang', 'Kudus', 'Pekalongan', 'Pemalang', 'Purworejo', 'Salatiga', 'Solo', 'Tegal', 'Temanggung', 'Wonosobo', 'Yogyakarta'] as $w)
                                    <option value="{{ $w }}" {{ old('wilayah') == $w ? 'selected' : '' }}>{{ $w }}</option>
                                @endforeach
                            </select>
                            @error('wilayah')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">Jenis Pekerjaan *</label>
                            <select id="jenis_pekerjaan" name="jenis_pekerjaan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 searchable-select">
                                <option value="">-- Pilih Jenis Pekerjaan --</option>
                                @foreach(['Project / Purchase Order', 'Instalasi Infrastruktur', 'Layanan Aktivasi Internet', 'Layanan Network & VPN', 'Managed Service & Maintenance'] as $jp)
                                    <option value="{{ $jp }}" {{ old('jenis_pekerjaan') == $jp ? 'selected' : '' }}>{{ $jp }}</option>
                                @endforeach
                            </select>
                            @error('jenis_pekerjaan')
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
                        <button type="button" onclick="submitForm()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium" title="Klik untuk menyimpan penawaran">
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
                    <select name="items[0][material_id]" required onchange="updateHargaAsli(this)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 material-select">
                        <option value="">-- Pilih Material --</option>
                        @foreach ($materials as $material)
                            @php
                                $typeDisplay = match($material->type) {
                                    'BARANG' => 'Barang',
                                    'JASA' => 'Jasa',
                                    'TOL' => 'Tol',
                                    'LAINNYA' => 'Lainnya',
                                    default => $material->type
                                };
                            @endphp
                            <option value="{{ $material->id }}" data-nama="{{ $material->nama }}" data-price="{{ $material->harga }}">
                                {{ $material->nama }} - {{ $material->satuan }} [{{ $typeDisplay }}]
                            </option>
                        @endforeach
                    </select>
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

        // Prevent form submission when Enter key is pressed in input fields
        // Only allow explicit button clicks for form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('penawaranForm');
            if (form) {
                form.addEventListener('keydown', function(e) {
                    // If Enter key is pressed
                    if (e.key === 'Enter') {
                        // Prevent default form submission
                        e.preventDefault();
                        
                        // Allow Enter only in textarea or if explicitly needed
                        if (e.target.tagName !== 'TEXTAREA') {
                            // Don't submit the form, just blur the field
                            e.target.blur();
                        }
                    }
                });
            }
        });

        // ============================================
        // FORM VALIDATION FUNCTION
        // ============================================
        function validateForm() {
            const errors = [];
            
            // 1. Check client is selected
            const clientId = document.getElementById('client_id').value;
            if (!clientId || clientId.trim() === '') {
                errors.push('✗ Pilih Client terlebih dahulu');
            }
            
            // 2. Check date is selected
            const tanggal = document.getElementById('tanggal').value;
            if (!tanggal) {
                errors.push('✗ Pilih Tanggal Penawaran');
            } else {
                const selectedDate = new Date(tanggal);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (selectedDate < today) {
                    errors.push('✗ Tanggal Penawaran tidak boleh di masa lalu');
                }
            }
            
            // 3. Check status is selected
            const status = document.getElementById('status').value;
            if (!status) {
                errors.push('✗ Pilih Status Penawaran');
            }
            
            // 4. Validate items
            const items = document.querySelectorAll('.item-row');
            if (items.length === 0) {
                errors.push('✗ Tambahkan minimal satu item');
            } else {
                let itemHasError = false;
                items.forEach((row, idx) => {
                    const materialId = row.querySelector('.material-select').value;
                    const jumlah = row.querySelector('.jumlah-input').value;
                    const hargaAsli = row.querySelector('.harga-asli-input').value;
                    const margin = row.querySelector('.margin-input').value;
                    
                    if (!materialId) {
                        errors.push(`✗ Item ${idx + 1}: Pilih Material`);
                        itemHasError = true;
                    }
                    
                    if (!jumlah || parseInt(jumlah) <= 0) {
                        errors.push(`✗ Item ${idx + 1}: Jumlah harus lebih dari 0`);
                        itemHasError = true;
                    }
                    
                    if (!hargaAsli || parseFloat(hargaAsli) <= 0) {
                        errors.push(`✗ Item ${idx + 1}: Harga Asli tidak valid`);
                        itemHasError = true;
                    }
                    
                    if (margin < 0 || margin > 100) {
                        errors.push(`✗ Item ${idx + 1}: Margin harus antara 0-100%`);
                        itemHasError = true;
                    }
                });
            }
            
            return {
                isValid: errors.length === 0,
                errors: errors
            };
        }

        // Explicit form submission function - triggered only by button click
        function submitForm() {
            const form = document.getElementById('penawaranForm');
            
            // Run comprehensive validation
            const validation = validateForm();
            
            if (!validation.isValid) {
                // Show all validation errors
                const errorMessage = 'Perbaiki kesalahan berikut sebelum menyimpan: ' + validation.errors.join(', ');
                showToast(errorMessage, 'error', 4000);
                console.warn('Form validation errors:', validation.errors);
                return;
            }
            
            // Submit form directly (no confirmation needed)
            form.submit();
        }

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
            
            // Initialize Choices for the new material select
            const newSelect = container.querySelector('.item-row:last-child .material-select');
            if (newSelect) {
                new Choices(newSelect, {
                    removeItemButton: true,
                    searchResultLimit: 6,
                    shouldSort: false, // Don't sort so it keeps the backend order
                    placeholder: true,
                    noResultsText: 'Tidak ada hasil pencarian',
                    noChoicesText: 'Tidak ada opsi tersedia',
                    itemSelectText: 'Tekan Enter untuk memilih'
                });
            }
        }

        function removeItem(btn) {
            btn.closest('.item-row').remove();
            calculateTotals();
        }

        function updateHargaAsli(selectElement) {
            const row = selectElement.closest('.item-row');
            // Check if Choices.js has wrapped it, if so, get the actual select options
            const originalSelect = selectElement;
            const selectedOption = originalSelect.options[originalSelect.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const hargaAsli = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                
                const hargaAsliInput = row.querySelector('.harga-asli-input');
                if(hargaAsliInput) {
                    hargaAsliInput.value = hargaAsli;
                }
            }
            
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

                // Harga jual per unit = harga asli + (harga asli * margin%)
                const hargaJual = hargaAsli + (hargaAsli * margin / 100);
                
                // Subtotal = harga jual * jumlah
                const subtotal = jumlah * hargaJual;
                
                // Cost total = harga asli * jumlah
                const costTotal = hargaAsli * jumlah;
                
                // Margin total = subtotal - cost total
                const marginTotal = subtotal - costTotal;

                // Update display
                row.querySelector('.harga-jual-display').textContent = 'Rp ' + number_format(hargaJual, 0, ',', '.');
                row.querySelector('.subtotal-display').textContent = 'Rp ' + number_format(subtotal, 0, ',', '.');

                totalBiaya += costTotal;
                totalMargin += marginTotal;
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




        // Add initial item on page load
        document.addEventListener('DOMContentLoaded', function() {
            addItem();
        });

        // ============================================
        // AI DSS ANALYSIS FOR MANUAL PENAWARAN
        // ============================================
        async function analyzeManualPenawaran() {
            // Get analyze button for loading state
            const analyzeBtn = event.target.closest('button');
            const originalBtnText = analyzeBtn.innerHTML;
            
            // Collect form data
            const clientId = document.getElementById('client_id').value;
            const tanggal = document.getElementById('tanggal').value;
            
            if (!clientId) {
                showToast('Pilih client terlebih dahulu', 'warning', 3000);
                return;
            }

            // Collect items
            const items = [];
            document.querySelectorAll('.item-row').forEach((row, index) => {
                const materialId = row.querySelector('.material-select').value;
                const jumlah = row.querySelector('.jumlah-input').value;
                const hargaAsli = row.querySelector('.harga-asli-input').value;
                const margin = row.querySelector('.margin-input').value;

                if (materialId && jumlah && hargaAsli) {
                    items.push({
                        material_id: parseInt(materialId),
                        jumlah: parseInt(jumlah),
                        harga_asli: parseFloat(hargaAsli),
                        persentase_margin: parseFloat(margin) || 0
                    });
                }
            });

            if (items.length === 0) {
                showToast('Tambahkan minimal satu item', 'warning', 3000);
                return;
            }

            // Show loading state on button
            analyzeBtn.disabled = true;
            analyzeBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menganalisis...';

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
                        wilayah: document.getElementById('wilayah').value,
                        jenis_pekerjaan: document.getElementById('jenis_pekerjaan').value,
                        items: items
                    })
                });

                const contentType = response.headers.get('content-type');
                
                // Handle rate limit error (429)
                if (response.status === 429) {
                    throw new Error('⏱️ Anda terlalu sering melakukan analisis. Tunggu beberapa saat sebelum mencoba lagi.');
                }
                
                // Check if response is JSON
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    throw new Error(`Server error: ${response.status} - ${text.substring(0, 100)}`);
                }

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || `Server error: ${response.status}`);
                }

                // Show analysis results
                showAnalysisResults(data);

            } catch (error) {
                console.error('Analysis error:', error);
                showToast(error.message, 'error', 3000);
            } finally {
                // Restore button state
                analyzeBtn.disabled = false;
                analyzeBtn.innerHTML = originalBtnText;
            }
        }

        function showAnalysisResults(data) {
            const modalId = 'analysisModal_' + Date.now();
            
            const grandTotal = data.data.grand_total;
            const prediksi = data.predictions?.lr || 0;
            const selisih = grandTotal - prediksi; // Positif jika Penawaran > Prediksi AI (Untung)
            const selisihPersen = Math.abs((selisih / grandTotal) * 100).toFixed(1);
            
            let selisihWarna, selisihBg, selisihLabel, badgeBgClass, badgeTextSymbol;
            let iconHtml;
            
            if (selisih < 0) {
                // Rugi (Merah)
                selisihWarna = 'text-red-600';
                selisihBg = 'bg-red-50 border-red-200';
                selisihLabel = 'Potensi Overrun / Risiko Defisit (Rugi)';
                badgeBgClass = 'bg-red-100 text-red-700';
                badgeTextSymbol = `-${selisihPersen}% Risiko Rugi`;
                iconHtml = '<svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
            } else if (selisihPersen < 10) {
                // Untung tapi tipis / warning (Kuning)
                selisihWarna = 'text-amber-600';
                selisihBg = 'bg-amber-50 border-amber-200';
                selisihLabel = 'Estimasi Margin Keuntungan Sangat Tipis (Rawan)';
                badgeBgClass = 'bg-amber-100 text-amber-700';
                badgeTextSymbol = `+${selisihPersen}% Untung (Tipis)`;
                iconHtml = '<svg class="w-6 h-6 sm:w-8 sm:h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
            } else {
                // Untung Sehat / Aman (Hijau)
                selisihWarna = 'text-green-600';
                selisihBg = 'bg-green-50 border-green-200';
                selisihLabel = 'Estimasi Margin Keuntungan Sehat (Aman)';
                badgeBgClass = 'bg-green-100 text-green-700';
                badgeTextSymbol = `+${selisihPersen}% Untung`;
                iconHtml = '<svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }
            
            const wilayahSelect = document.getElementById('wilayah');
            const wilayah = wilayahSelect.options[wilayahSelect.selectedIndex]?.text || 'wilayah ini';
            
            const jenisSelect = document.getElementById('jenis_pekerjaan');
            const jenisPekerjaan = jenisSelect.options[jenisSelect.selectedIndex]?.text || 'jenis pekerjaan ini';

            const analysisHTML = `
                <div id="${modalId}" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-gray-900/60 p-4 transition-opacity">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full flex flex-col max-h-[90vh] overflow-hidden transform transition-all animate-fade-in-up">
                        
                        <!-- Header Gradient - Premium Sleek Charcoal & Indigo Dark-mode -->
                        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 p-4 sm:p-5 text-white flex justify-between items-center flex-shrink-0 border-b border-indigo-900/40">
                            <div>
                                <h2 class="text-xl sm:text-2xl font-bold flex items-center gap-2 text-white">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    Hasil Analisis Prediksi AI
                                </h2>
                                <p class="text-slate-300 text-xs sm:text-sm mt-1 opacity-95">Evaluasi kelayakan penawaran berbasis Machine Learning.</p>
                            </div>
                            <button onclick="closeAnalysisModal('${modalId}')" class="text-slate-400 hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4 overflow-y-auto flex-1">
                            
                            <!-- Insight Text -->
                            <div class="bg-gray-50 rounded-xl p-3 sm:p-4 border border-gray-200 text-gray-700 text-xs sm:text-sm leading-relaxed shadow-sm">
                                <p>Berdasarkan data historis proyek <strong>${jenisPekerjaan}</strong> di <strong>${wilayah}</strong>, model AI memprediksi bahwa total biaya aktual yang sesungguhnya akan dikeluarkan mungkin berbeda dengan nilai estimasi penawaran Anda.</p>
                            </div>

                            <!-- Comparison Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                                <!-- Nilai Penawaran -->
                                <div class="p-4 rounded-xl border border-gray-200 bg-white shadow-sm flex flex-col justify-center">
                                    <span class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Nilai Penawaran (Estimasi Anda)</span>
                                    <span class="text-xl sm:text-2xl font-bold text-gray-900">Rp ${grandTotal.toLocaleString('id-ID')}</span>
                                </div>
                                
                                <!-- Prediksi AI -->
                                <div class="p-4 rounded-xl border border-purple-200 bg-purple-50 flex flex-col justify-center relative overflow-hidden shadow-sm">
                                    <span class="text-xs sm:text-sm font-medium text-purple-800 mb-1">Prediksi Pengeluaran Nyata (AI)</span>
                                    <span class="text-xl sm:text-2xl font-bold text-purple-900 relative z-10">Rp ${prediksi.toLocaleString('id-ID')}</span>
                                    <svg class="absolute -bottom-4 -right-4 w-20 h-20 text-purple-200 opacity-50 z-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                                </div>
                            </div>

                            <!-- Variance Indicator -->
                            <div class="flex items-center p-4 rounded-xl border ${selisihBg} shadow-sm">
                                <div class="flex-1">
                                    <p class="text-xs sm:text-sm font-bold text-gray-600 mb-1">${selisihLabel}</p>
                                    <div class="flex items-end gap-2">
                                        <span class="text-xl sm:text-2xl font-bold ${selisihWarna}">Rp ${Math.abs(selisih).toLocaleString('id-ID')}</span>
                                        <span class="text-xs sm:text-sm font-bold px-2 py-0.5 rounded-full ${badgeBgClass} mb-1">
                                            ${badgeTextSymbol}
                                        </span>
                                    </div>
                                    <p class="text-[11px] sm:text-xs text-gray-500 mt-1">Selisih antara pengeluaran nyata dengan penawaran.</p>
                                </div>
                                <div class="flex-shrink-0 bg-white p-2.5 rounded-full shadow-sm">
                                    ${iconHtml}
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            <!-- Recommendation Section -->
                            <div class="flex flex-col md:flex-row gap-4 items-stretch">
                                <!-- Risk Badge -->
                                <div class="flex-shrink-0 text-center p-3 rounded-xl ${getRiskBgColor(data.risk_level)} w-full md:w-32 border shadow-sm flex flex-col justify-center items-center">
                                    <span class="block text-[10px] sm:text-xs text-gray-500 uppercase tracking-wider mb-1 font-bold">Risiko</span>
                                    <span class="inline-flex items-center justify-center font-black text-lg sm:text-xl text-gray-900 gap-1">
                                        ${getRiskEmoji(data.risk_level)} ${data.risk_level}
                                    </span>
                                </div>
                                
                                <!-- AI Text -->
                                <div class="flex-1 flex flex-col justify-center">
                                    <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 mb-1 uppercase tracking-wide">Kesimpulan & Rekomendasi</h4>
                                    <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded-r-lg">
                                        <p class="text-xs sm:text-sm text-blue-900 leading-relaxed font-medium">${data.recommendation}</p>
                                    </div>
                                </div>
                             </div>

                             <!-- Model Comparison Section -->
                             <div class="mt-4 pt-4 border-t border-gray-100">
                                 <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 mb-3 uppercase tracking-wide flex items-center gap-2">
                                     <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                     Perbandingan Estimasi Multi-Model AI
                                 </h4>
                                 <p class="text-[11px] text-gray-500 mb-3">Model diurutkan berdasarkan peringkat performa global hasil uji akademik (*Multi-Metric Borda Count*).</p>
                                 <div class="space-y-2.5">
                                  ${[
                                         {
                                             id: 'lr',
                                             name: 'Linear Regression',
                                             rank: 1,
                                             r2: '95.5%',
                                             mape: '25.8%',
                                             mse: '0.0013',
                                             rmse: '0.0359',
                                             pred: data.predictions_meta?.linear_regression?.prediction || 0,
                                             time: data.predictions_meta?.linear_regression?.time_ms || 0,
                                             color: 'from-blue-500 to-cyan-500'
                                         },
                                         {
                                             id: 'rf',
                                             name: 'Random Forest Ensemble',
                                             rank: 2,
                                             r2: '94.6%',
                                             mape: '17.7%',
                                             mse: '0.0015',
                                             rmse: '0.0393',
                                             pred: data.predictions_meta?.random_forest?.prediction || 0,
                                             time: data.predictions_meta?.random_forest?.time_ms || 0,
                                             color: 'from-purple-500 to-indigo-500'
                                         },
                                         {
                                             id: 'xgb',
                                             name: 'XGBoost Boosting',
                                             rank: 3,
                                             r2: '94.5%',
                                             mape: '17.9%',
                                             mse: '0.0016',
                                             rmse: '0.0399',
                                             pred: data.predictions_meta?.xgboost?.prediction || 0,
                                             time: data.predictions_meta?.xgboost?.time_ms || 0,
                                             color: 'from-orange-500 to-red-500'
                                         },
                                         {
                                             id: 'dl',
                                             name: 'Deep Learning (ANN)',
                                             rank: 4,
                                             r2: '95.0%',
                                             mape: '29.7%',
                                             mse: '0.0015',
                                             rmse: '0.0381',
                                             pred: data.predictions_meta?.deep_learning?.prediction || 0,
                                             time: data.predictions_meta?.deep_learning?.time_ms || 0,
                                             color: 'from-pink-500 to-rose-500'
                                         },
                                         {
                                             id: 'ma',
                                             name: 'Moving Average',
                                             rank: 5,
                                             r2: '93.7%',
                                             mape: '18.3%',
                                             mse: '0.0018',
                                             rmse: '0.0423',
                                             pred: data.predictions_meta?.moving_average?.prediction || 0,
                                             time: data.predictions_meta?.moving_average?.time_ms || 0,
                                             color: 'from-gray-500 to-slate-500'
                                         }
                                     ].map(m => {
                                         const mSelisih = grandTotal - m.pred;
                                         const mPersen = Math.abs((mSelisih / grandTotal) * 100).toFixed(1);
                                         const mIsUntung = mSelisih >= 0;
                                         
                                         const badgeBg = mIsUntung ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200';
                                         const badgeText = mIsUntung ? `+${mPersen}% Untung` : `-${mPersen}% Risiko Rugi`;
                                         
                                         const rankColors = m.rank === 1 ? 'bg-amber-100 text-amber-800 border border-amber-200 font-extrabold'
                                                          : m.rank === 2 ? 'bg-purple-100 text-purple-800 border border-purple-200 font-bold'
                                                          : m.rank === 3 ? 'bg-orange-100 text-orange-800 border border-orange-200 font-bold'
                                                          : m.rank === 4 ? 'bg-pink-100 text-pink-800 border border-pink-200 font-bold'
                                                          : 'bg-slate-100 text-slate-700 border border-slate-200 font-semibold';
                                         
                                         return `
                                             <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-3.5 rounded-xl border border-gray-150 bg-white hover:border-purple-300 hover:shadow-md transition duration-200 gap-2 relative overflow-hidden group">
                                                 <div class="absolute top-0 left-0 bottom-0 w-1 bg-gradient-to-b ${m.color}"></div>
                                                 
                                                 <div class="flex items-center gap-3 pl-2 flex-1 min-w-0">
                                                     <div class="w-8 h-8 rounded-full ${rankColors} flex items-center justify-center text-xs flex-shrink-0">
                                                         #${m.rank}
                                                     </div>
                                                     
                                                     <div class="min-w-0 flex-1">
                                                         <div class="flex items-center gap-2">
                                                             <span class="text-xs sm:text-sm font-bold text-gray-900 truncate">${m.name}</span>
                                                         </div>
                                                         <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                                             <span class="text-[9px] px-1.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-medium font-mono border border-blue-100">R²: ${m.r2}</span>
                                                             <span class="text-[9px] px-1.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-medium font-mono border border-indigo-100">MAPE: ${m.mape}</span>
                                                             <span class="text-[9px] px-1.5 py-0.5 rounded-md bg-purple-50 text-purple-700 font-medium font-mono border border-purple-100">MSE: ${m.mse}</span>
                                                             <span class="text-[9px] px-1.5 py-0.5 rounded-md bg-pink-50 text-pink-700 font-medium font-mono border border-pink-100">RMSE: ${m.rmse}</span>
                                                             <div class="flex items-center gap-1 text-[9px] text-gray-500 font-sans ml-1 bg-gray-50 border border-gray-200 rounded-md px-1.5 py-0.5">
                                                                 <svg class="w-3 h-3 text-purple-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                                 <span>${m.time.toFixed(1)} ms</span>
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 
                                                 <div class="flex items-center gap-3 pl-11 sm:pl-0 w-full sm:w-auto justify-between sm:justify-end flex-shrink-0">
                                                     <div class="text-right">
                                                         <span class="block text-xs sm:text-sm font-black text-gray-900">Rp ${m.pred.toLocaleString('id-ID')}</span>
                                                     </div>
                                                     <span class="text-[10px] sm:text-xs font-bold px-2.5 py-1 rounded-lg border ${badgeBg} min-w-[95px] text-center shadow-sm">
                                                         ${badgeText}
                                                     </span>
                                                 </div>
                                             </div>
                                         `;
                                     }).join('')}
                                 </div>
                             </div>
                         </div>

                        <!-- Footer Actions -->
                        <div class="bg-gray-50 border-t border-gray-200 p-4 flex flex-col sm:flex-row gap-3 justify-end items-center flex-shrink-0">
                            <button type="button" onclick="closeAnalysisModal('${modalId}')" class="w-full sm:w-auto px-5 py-2 bg-white border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-100 transition shadow-sm text-sm">
                                Kembali ke Form
                            </button>
                            <button type="button" onclick="closeAnalysisModal('${modalId}'); submitForm()" class="w-full sm:w-auto px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-indigo-700 transition shadow-md flex justify-center items-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Lanjut Simpan
                            </button>
                        </div>

                    </div>
                </div>
                <style>
                    @keyframes fadeInUp {
                        from { opacity: 0; transform: translateY(10px) scale(0.98); }
                        to { opacity: 1; transform: translateY(0) scale(1); }
                    }
                    .animate-fade-in-up {
                        animation: fadeInUp 0.3s ease-out forwards;
                    }
                </style>
            `;

            document.body.insertAdjacentHTML('beforeend', analysisHTML);
        }

        function closeAnalysisModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.remove();
            }
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
    </script>
@endsection
