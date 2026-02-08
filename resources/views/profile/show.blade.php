@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
            </div>

            @if (session('success'))
                <div class="mx-6 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="mx-6 mt-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
                    {{ session('info') }}
                </div>
            @endif

            <div class="p-6">
                <!-- User Info Box -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                            {{ strtoupper(substr($profil->nama_depan ?? auth()->user()->name, 0, 1)) }}{{ strtoupper(substr($profil->nama_belakang ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $profil->nama_lengkap ?? auth()->user()->name }}</h2>
                            <p class="text-gray-600">{{ auth()->user()->email }}</p>
                            <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full text-sm font-medium {{ auth()->user()->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Profil</h3>
                    <div class="space-y-4">
                        <div class="flex items-center border-b border-gray-200 pb-4">
                            <div class="w-40 font-medium text-gray-600">Nama Depan</div>
                            <div class="text-gray-900 font-medium">{{ $profil->nama_depan ?? '-' }}</div>
                        </div>

                        <div class="flex items-center border-b border-gray-200 pb-4">
                            <div class="w-40 font-medium text-gray-600">Nama Belakang</div>
                            <div class="text-gray-900 font-medium">{{ $profil->nama_belakang ?? '-' }}</div>
                        </div>

                        <div class="flex items-center border-b border-gray-200 pb-4">
                            <div class="w-40 font-medium text-gray-600">Email</div>
                            <div class="text-gray-900 font-medium">{{ auth()->user()->email }}</div>
                        </div>

                        <div class="flex items-center border-b border-gray-200 pb-4">
                            <div class="w-40 font-medium text-gray-600">Telepon</div>
                            <div class="text-gray-900 font-medium">{{ $profil->telepon ?: '-' }}</div>
                        </div>

                        <div class="flex items-center border-b border-gray-200 pb-4">
                            <div class="w-40 font-medium text-gray-600">Role</div>
                            <div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ auth()->user()->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center border-b border-gray-200 pb-4">
                            <div class="w-40 font-medium text-gray-600">Dibuat</div>
                            <div class="text-gray-900">{{ $profil->created_at ? $profil->created_at->format('d/m/Y H:i') : '-' }}</div>
                        </div>

                        <div class="flex items-center">
                            <div class="w-40 font-medium text-gray-600">Terakhir Update</div>
                            <div class="text-gray-900">{{ $profil->updated_at ? $profil->updated_at->format('d/m/Y H:i') : '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex gap-4">
                    <a 
                        href="{{ route('profile.edit') }}" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Profil
                    </a>
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
