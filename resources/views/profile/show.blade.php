@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
              
            </div>

            @if (session('info'))
                <div class="mx-6 mt-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
                    {{ session('info') }}
                </div>
            @endif

            <div class="p-6">
                <!-- User Info Box -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($profil->nama_depan, 0, 1) . substr($profil->nama_belakang, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $profil->nama_lengkap }}</h2>
                            <p class="text-gray-600">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-32 font-medium text-gray-700">Nama Depan:</div>
                        <div class="text-gray-900">{{ $profil->nama_depan }}</div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 flex items-start gap-4">
                        <div class="w-32 font-medium text-gray-700">Nama Belakang:</div>
                        <div class="text-gray-900">{{ $profil->nama_belakang }}</div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 flex items-start gap-4">
                        <div class="w-32 font-medium text-gray-700">Email:</div>
                        <div class="text-gray-900">{{ auth()->user()->email }}</div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 flex items-start gap-4">
                        <div class="w-32 font-medium text-gray-700">Telepon:</div>
                        <div class="text-gray-900">
                            {{ $profil->telepon ?: '-' }}
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 flex items-start gap-4">
                        <div class="w-32 font-medium text-gray-700">Dibuat:</div>
                        <div class="text-gray-900">{{ $profil->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 flex items-start gap-4">
                        <div class="w-32 font-medium text-gray-700">Update:</div>
                        <div class="text-gray-900">{{ $profil->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex gap-4">
                    <a 
                        href="{{ route('profile.edit') }}" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                    >
                        Edit Profil
                    </a>
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition"
                    >
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
