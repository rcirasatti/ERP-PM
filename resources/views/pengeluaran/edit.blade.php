@extends('layouts.app')

@section('title', 'Edit Pengeluaran')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Pengeluaran</h1>
            <p class="text-gray-600 mt-2">Ubah data pengeluaran proyek</p>
        </div>
        <a href="{{ route('pengeluaran.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-8">
                <!-- Error Messages -->
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

                <form action="{{ route('pengeluaran.update', $pengeluaran->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Hidden fields untuk tracking dari mana request datang -->
                    <input type="hidden" name="from" value="{{ request('from', 'index') }}">
                    <input type="hidden" name="budget_id" value="{{ request('budget_id', '') }}">

                    <!-- Proyek -->
                    <div>
                        <label for="proyek_id" class="block text-sm font-medium text-gray-700 mb-2">Proyek *</label>
                        <select name="proyek_id" id="proyek_id" required
                            class="searchable-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('proyek_id') border-red-500 @enderror">
                            <option value="">-- Pilih Proyek --</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{ old('proyek_id', $pengeluaran->proyek_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('proyek_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal *</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $pengeluaran->tanggal->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('tanggal') border-red-500 @enderror">
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                        <select name="kategori" id="kategori" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('kategori') border-red-500 @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="material" {{ old('kategori', $pengeluaran->kategori) == 'material' ? 'selected' : '' }}>Material</option>
                            <option value="gaji" {{ old('kategori', $pengeluaran->kategori) == 'gaji' ? 'selected' : '' }}>Gaji</option>
                            <option value="bahan_bakar" {{ old('kategori', $pengeluaran->kategori) == 'bahan_bakar' ? 'selected' : '' }}>Bahan Bakar</option>
                            <option value="peralatan" {{ old('kategori', $pengeluaran->kategori) == 'peralatan' ? 'selected' : '' }}>Peralatan</option>
                            <option value="lainnya" {{ old('kategori', $pengeluaran->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('deskripsi') border-red-500 @enderror"
                            placeholder="Masukkan deskripsi pengeluaran secara detail">{{ old('deskripsi', $pengeluaran->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp) *</label>
                        <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', $pengeluaran->jumlah) }}" step="0.01" min="0" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('jumlah') border-red-500 @enderror"
                            placeholder="Contoh: 500000">
                        @error('jumlah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bukti File -->
                    <div>
                        <label for="bukti_file" class="block text-sm font-medium text-gray-700 mb-2">Bukti (Upload File)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition" id="dropZone">
                            <input type="file" name="bukti_file" id="bukti_file" class="hidden"
                                accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx">
                            
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            
                            <p class="text-gray-700 font-medium">Drag & drop file atau <span class="text-blue-600 hover:text-blue-700">klik untuk upload</span></p>
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG, GIF, DOC, DOCX, XLS, XLSX (Max 5 MB)</p>
                            
                            <div id="fileInfo" class="mt-3 text-sm text-gray-600"></div>
                        </div>
                        
                        @if($pengeluaran->bukti_file)
                            <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-sm text-green-800 font-medium">File saat ini:</p>
                                <a href="{{ route('pengeluaran.preview-bukti', $pengeluaran->id) }}" target="_blank" class="text-blue-600 hover:text-blue-700 underline text-sm">
                                    {{ basename($pengeluaran->bukti_file) }}
                                </a>
                            </div>
                        @endif
                        
                        @error('bukti_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <script>
                        // File upload dengan drag & drop
                        const dropZone = document.getElementById('dropZone');
                        const fileInput = document.getElementById('bukti_file');
                        const fileInfo = document.getElementById('fileInfo');

                        // Click to upload
                        dropZone.addEventListener('click', () => fileInput.click());

                        // Drag & drop events
                        dropZone.addEventListener('dragover', (e) => {
                            e.preventDefault();
                            dropZone.classList.add('border-blue-500', 'bg-blue-50');
                        });

                        dropZone.addEventListener('dragleave', () => {
                            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
                        });

                        dropZone.addEventListener('drop', (e) => {
                            e.preventDefault();
                            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
                            fileInput.files = e.dataTransfer.files;
                            updateFileInfo();
                        });

                        // File input change
                        fileInput.addEventListener('change', updateFileInfo);

                        function updateFileInfo() {
                            if (fileInput.files.length > 0) {
                                const file = fileInput.files[0];
                                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                                fileInfo.innerHTML = `<span class="text-green-600">✓ ${file.name} (${sizeMB} MB)</span>`;
                            } else {
                                fileInfo.innerHTML = '';
                            }
                        }
                    </script>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-6">
                        <button type="submit" id="submitBtn" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Perbarui</span>
                        </button>
                        <a href="{{ route('pengeluaran.index') }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium text-center">
                            Batal
                        </a>
                    </div>

                    <script>
                        // Form validation untuk field wajib
                        const form = document.querySelector('form');
                        
                        form.addEventListener('submit', function(e) {
                            const requiredFields = [];
                            
                            // Check Proyek
                            const proyekInput = document.getElementById('proyek_id');
                            if (!proyekInput.value) {
                                requiredFields.push('Proyek');
                            }
                            
                            // Check Tanggal
                            const tanggalInput = document.getElementById('tanggal');
                            if (!tanggalInput.value) {
                                requiredFields.push('Tanggal');
                            }
                            
                            // Check Kategori
                            const kategoriInput = document.getElementById('kategori');
                            if (!kategoriInput.value) {
                                requiredFields.push('Kategori');
                            }
                            
                            // Check Deskripsi
                            const deskripsiInput = document.getElementById('deskripsi');
                            if (!deskripsiInput.value.trim()) {
                                requiredFields.push('Deskripsi');
                            }
                            
                            // Check Jumlah
                            const jumlahInput = document.getElementById('jumlah');
                            if (!jumlahInput.value) {
                                requiredFields.push('Jumlah');
                            }
                            
                            if (requiredFields.length > 0) {
                                e.preventDefault();
                                const fieldList = requiredFields.join(',\n• ');
                                alert('⚠️ Field wajib diisi:\n\n• ' + fieldList + '\n\nMohon lengkapi semua field yang diperlukan!');
                                return false;
                            }
                        });
                    </script>
                </form>
            </div>
        </div>

        <!-- Info Section -->
        <div class="lg:col-span-1">
            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                <h3 class="font-semibold text-blue-900 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Informasi Pengeluaran</span>
                </h3>
                <div class="space-y-3 text-sm text-blue-800">
                    <div>
                        <strong>Dibuat Oleh:</strong><br>
                        {{ $pengeluaran->creator->name ?? 'N/A' }}
                    </div>
                    <div>
                        <strong>Tanggal Dibuat:</strong><br>
                        {{ $pengeluaran->created_at->format('d M Y H:i') }}
                    </div>
                    <div>
                        <strong>Terakhir Diubah:</strong><br>
                        {{ $pengeluaran->updated_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-6 border border-green-200 mt-6">
                <h3 class="font-semibold text-green-900 mb-3">Kategori Pengeluaran</h3>
                <div class="space-y-2 text-sm text-green-800">
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">Material</span>
                        <span>Pembelian bahan baku</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Gaji</span>
                        <span>Pembayaran upah kerja</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Bahan Bakar</span>
                        <span>Biaya transportasi</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">Peralatan</span>
                        <span>Pembelian alat kerja</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">Lainnya</span>
                        <span>Pengeluaran lain-lain</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
