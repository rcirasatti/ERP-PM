@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('proyek.show', $proyek->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Detail Project
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Task</h1>
        <p class="text-gray-600 mt-1">{{ $tugas->nama }}</p>
    </div>

    <div class="max-w-2xl">
        <form action="{{ route('proyek.tugas.update', [$proyek->id, $tugas->id]) }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <!-- Task Name -->
            <div class="mb-6">
                <label for="nama" class="block text-sm font-medium text-gray-900 mb-2">
                    Nama Task <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" id="nama" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                       value="{{ old('nama', $tugas->nama) }}" required>
                @error('nama')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Selesai Status -->
            <div class="mb-6">
                <label for="selesai" class="block text-sm font-medium text-gray-900 mb-2">
                    Status
                </label>
                <div class="flex items-center gap-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="hidden" name="selesai" value="0">
                        <input type="checkbox" name="selesai" value="1" 
                               class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                               @checked(old('selesai', $tugas->selesai))>
                        <span class="ml-2 text-gray-700">Tandai Sebagai Selesai</span>
                    </label>
                </div>
                @error('selesai')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('proyek.show', $proyek->id) }}" class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition font-medium">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
