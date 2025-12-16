@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">Edit Profil</h1>
            </div>

            @if ($errors->any())
                <div class="mx-6 mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mx-6 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Depan -->
                    <div>
                        <label for="nama_depan" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Depan <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nama_depan" 
                            name="nama_depan" 
                            value="{{ old('nama_depan', $profil->nama_depan) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_depan') border-red-500 @enderror"
                            required
                        >
                        @error('nama_depan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Belakang -->
                    <div>
                        <label for="nama_belakang" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Belakang <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nama_belakang" 
                            name="nama_belakang" 
                            value="{{ old('nama_belakang', $profil->nama_belakang) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_belakang') border-red-500 @enderror"
                            required
                        >
                        @error('nama_belakang')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="mt-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        required
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telepon -->
                <div class="mt-6">
                    <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Telepon (Opsional)
                    </label>
                    <input 
                        type="tel" 
                        id="telepon" 
                        name="telepon" 
                        value="{{ old('telepon', $profil->telepon) }}"
                        placeholder="08xx-xxxx-xxxx"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telepon') border-red-500 @enderror"
                    >
                    @error('telepon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mt-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru (Kosongkan jika tidak ingin mengubah)
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                    >
                    <p class="text-sm text-gray-500 mt-1">Minimal 8 karakter</p>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mt-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password_confirmation') border-red-500 @enderror"
                    >
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="mt-8 flex gap-4">
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                    >
                        Simpan Perubahan
                    </button>
                    <a 
                        href="{{ route('profile.show') }}" 
                        class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition"
                    >
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
