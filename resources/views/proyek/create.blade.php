@extends('layouts.app')

@section('title', 'Buat Project')

@section('content')
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('proyek.index') }}" class="hover:text-blue-600">Projects</a>
            <span>/</span>
            <span>Buat Project</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Buat Project Baru</h2>

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

                <form action="{{ route('proyek.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Penawaran Selection -->
                    <div>
                        <label for="penawaran_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Penawaran yang Disetujui <span class="text-red-500">*</span>
                        </label>
                        <select name="penawaran_id" id="penawaran_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('penawaran_id') border-red-500 @enderror" required onchange="updatePenawaranInfo()">
                            <option value="">-- Pilih Penawaran --</option>
                            @foreach($penawaran_disetujui as $penawaran)
                                <option value="{{ $penawaran->id }}" data-client="{{ $penawaran->client->nama }}" data-no="{{ $penawaran->no_penawaran }}" data-biaya="{{ $penawaran->total_biaya }}">
                                    {{ $penawaran->no_penawaran }} - {{ $penawaran->client->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('penawaran_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penawaran Info -->
                    <div id="penawaran-info" class="p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-medium text-blue-600 uppercase">No. Penawaran</p>
                                <p class="text-sm font-semibold text-gray-900" id="no-penawaran">-</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-blue-600 uppercase">Client</p>
                                <p class="text-sm font-semibold text-gray-900" id="client-penawaran">-</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs font-medium text-blue-600 uppercase">Total Biaya</p>
                                <p class="text-sm font-semibold text-gray-900">Rp <span id="biaya-penawaran">0</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Project Name -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Project <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror"
                               placeholder="Contoh: Instalasi Listrik Gedung A" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" value="{{ old('deskripsi') }}"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Deskripsi singkat project...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi
                        </label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('lokasi') border-red-500 @enderror"
                               placeholder="Contoh: Jakarta, Bandung, Surabaya">
                        @error('lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_mulai') border-red-500 @enderror" required>
                            @error('tanggal_mulai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Selesai
                            </label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_selesai') border-red-500 @enderror">
                            @error('tanggal_selesai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-3 pt-6">
                        <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Buat Project
                        </button>
                        <a href="{{ route('proyek.index') }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium text-center">
                            Batal
                        </a>
                    </div>
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
                    <span>Petunjuk</span>
                </h3>
                <ul class="space-y-3 text-sm text-blue-800">
                    <li class="flex space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Penawaran:</strong> Hanya penawaran dengan status "Disetujui" yang dapat dipilih</span>
                    </li>
                    <li class="flex space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Satu Project:</strong> Setiap penawaran hanya dapat membuat satu project</span>
                    </li>
                    <li class="flex space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Tanggal Selesai:</strong> Harus sama atau setelah tanggal mulai</span>
                    </li>
                    <li class="flex space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Status Otomatis:</strong> Project dimulai dengan status "Baru"</span>
                    </li>
                    <li class="flex space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Progress Otomatis:</strong> Progress dihitung dari task yang diselesaikan</span>
                    </li>
                    <li class="flex space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Centang Task:</strong> Perubahan tersimpan otomatis saat dicenang/dihilangkan</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script>
    function updatePenawaranInfo() {
        const select = document.getElementById('penawaran_id');
        const option = select.options[select.selectedIndex];
        const infoDiv = document.getElementById('penawaran-info');
        
        if (option.value) {
            document.getElementById('no-penawaran').textContent = option.getAttribute('data-no');
            document.getElementById('client-penawaran').textContent = option.getAttribute('data-client');
            document.getElementById('biaya-penawaran').textContent = new Intl.NumberFormat('id-ID').format(option.getAttribute('data-biaya'));
            infoDiv.classList.remove('hidden');
        } else {
            infoDiv.classList.add('hidden');
        }
    }
    </script>
@endsection
