@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('user.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Detail User</h1>
        </div>

        <!-- User Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- User Header -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-8">
                <div class="flex items-center">
                    <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center text-3xl font-bold text-blue-600">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="ml-6 text-white">
                        <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                        <p class="text-blue-100">{{ $user->email }}</p>
                        <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full text-sm font-medium {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- User Details -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Profil</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center border-b border-gray-200 pb-4">
                        <div class="w-40 font-medium text-gray-600">Nama Depan</div>
                        <div class="text-gray-900">{{ $user->profil->nama_depan ?? '-' }}</div>
                    </div>

                    <div class="flex items-center border-b border-gray-200 pb-4">
                        <div class="w-40 font-medium text-gray-600">Nama Belakang</div>
                        <div class="text-gray-900">{{ $user->profil->nama_belakang ?? '-' }}</div>
                    </div>

                    <div class="flex items-center border-b border-gray-200 pb-4">
                        <div class="w-40 font-medium text-gray-600">Email</div>
                        <div class="text-gray-900">{{ $user->email }}</div>
                    </div>

                    <div class="flex items-center border-b border-gray-200 pb-4">
                        <div class="w-40 font-medium text-gray-600">Telepon</div>
                        <div class="text-gray-900">{{ $user->profil->telepon ?: '-' }}</div>
                    </div>

                    <div class="flex items-center border-b border-gray-200 pb-4">
                        <div class="w-40 font-medium text-gray-600">Role</div>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center border-b border-gray-200 pb-4">
                        <div class="w-40 font-medium text-gray-600">Dibuat</div>
                        <div class="text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="flex items-center">
                        <div class="w-40 font-medium text-gray-600">Terakhir Update</div>
                        <div class="text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex gap-4">
                    <a href="{{ route('user.edit', $user) }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit User
                    </a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('user.destroy', $user) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
