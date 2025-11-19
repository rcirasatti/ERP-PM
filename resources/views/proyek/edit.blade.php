@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('proyek.index') }}" class="hover:text-blue-600">Projects</a>
            <span>/</span>
            <span>{{ $proyek->nama }}</span>
            <span>/</span>
            <span>Edit</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Edit Project</h2>
                <p class="text-gray-600 mb-6">Perbarui informasi project seperti nama, deskripsi, lokasi, tanggal, dan status. Data seperti client dan penawaran tidak dapat diubah.</p>

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

                <form action="{{ route('proyek.update', $proyek->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Project Name -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Project <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $proyek->nama) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $proyek->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi
                        </label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $proyek->lokasi) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('lokasi') border-red-500 @enderror">
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
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $proyek->tanggal_mulai?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_mulai') border-red-500 @enderror" required>
                            @error('tanggal_mulai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $proyek->tanggal_selesai?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_selesai') border-red-500 @enderror" required>
                            @error('tanggal_selesai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-3 pt-6">
                        <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('proyek.show', $proyek->id) }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium text-center">
                            Batal
                        </a>
                    </div>
                </form>
                @include('components.form-validation')
            </div>
        </div>

        <!-- Info Section -->
        <div class="lg:col-span-1">
            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200 mb-6">
                <h3 class="font-semibold text-blue-900 mb-4">Informasi Project</h3>
                <dl class="space-y-3 text-sm">
                    <div class="pb-3 border-b border-blue-100">
                        <dt class="font-medium text-blue-600 mb-1">Client</dt>
                        <dd class="text-gray-900">{{ $proyek->client->nama ?? 'N/A' }}</dd>
                    </div>
                    <div class="pb-3 border-b border-blue-100">
                        <dt class="font-medium text-blue-600 mb-1">No. Penawaran</dt>
                        <dd class="text-gray-900">{{ $proyek->penawaran->no_penawaran ?? 'N/A' }}</dd>
                    </div>
                    <div class="pb-3 border-b border-blue-100">
                        <dt class="font-medium text-blue-600 mb-1">Status Saat Ini</dt>
                        <dd>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $proyek->getStatusColor() }}">
                                {{ $proyek->getStatusLabel() }}
                            </span>
                        </dd>
                    </div>
                    <div class="pb-3 border-b border-blue-100">
                        <dt class="font-medium text-blue-600 mb-1">Progress Saat Ini</dt>
                        <dd>
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-gray-900">{{ number_format($proyek->persentase_progres, 0) }}%</span>
                            </div>
                            <div class="w-full h-2 bg-blue-100 rounded-full overflow-hidden">
                                <div class="h-2 rounded-full {{ $proyek->getProgressColor() }}" 
                                     style="width: {{ $proyek->persentase_progres }}%"></div>
                            </div>
                        </dd>
                    </div>
                    <div class="pb-3 border-b border-blue-100">
                        <dt class="font-medium text-blue-600 mb-1">Dibuat Pada</dt>
                        <dd class="text-gray-900">{{ $proyek->created_at?->format('d M Y H:i') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-blue-600 mb-1">Diperbarui</dt>
                        <dd class="text-gray-900">{{ $proyek->updated_at?->format('d M Y H:i') ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                <h3 class="font-semibold text-yellow-900 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Catatan</span>
                </h3>
                <ul class="space-y-2 text-sm text-yellow-800">
                    <li class="flex space-x-2">
                        <span class="text-yellow-600 font-bold">•</span>
                        <span>Perubahan akan langsung tersimpan setelah diklik "Simpan"</span>
                    </li>
                    <li class="flex space-x-2">
                        <span class="text-yellow-600 font-bold">•</span>
                        <span>Tanggal selesai harus sama atau setelah tanggal mulai</span>
                    </li>
                    <li class="flex space-x-2">
                        <span class="text-yellow-600 font-bold">•</span>
                        <span><strong>Status Otomatis:</strong> Tidak ada task = "Baru", Ada task tapi belum 100% = "Instalasi", Progress 100% = "Selesai"</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            showToast("{{ session('success') }}", 'success');
        </script>
    @endif
@endsection
