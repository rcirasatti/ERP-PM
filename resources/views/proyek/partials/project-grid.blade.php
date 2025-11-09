@forelse ($proyeks as $item)
    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-5 border border-gray-100" data-project-id="{{ $item->id }}">
        <!-- Header -->
        <div class="flex justify-between items-start mb-3">
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 text-sm line-clamp-2">{{ $item->nama }}</h3>
                <p class="text-xs text-gray-600 mt-1">{{ $item->client->nama ?? 'N/A' }}</p>
            </div>
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2 {{ $item->getStatusColor() }}">
                {{ $item->getStatusLabel() }}
            </span>
        </div>

        <!-- Date -->
        <div class="flex items-center text-xs text-gray-500 mb-3">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '-' }}
        </div>

        <!-- Progress -->
        <div class="mb-3">
            <div class="flex justify-between items-center mb-1">
                <span class="text-xs font-medium text-gray-700">Progress</span>
                <span class="text-xs font-bold text-gray-900">{{ number_format($item->persentase_progres, 0) }}%</span>
            </div>
            <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-2 rounded-full {{ $item->getProgressColor() }}" 
                     style="width: {{ $item->persentase_progres }}%"></div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-2 pt-2 border-t border-gray-100">
            <a href="{{ route('proyek.show', $item->id) }}" class="flex-1 text-center px-2 py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-50 rounded transition" title="Lihat Detail">
                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </a>
            <a href="{{ route('proyek.edit', $item->id) }}" class="flex-1 text-center px-2 py-1.5 text-xs font-medium text-yellow-600 hover:bg-yellow-50 rounded transition" title="Edit Project">
                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            <button onclick="deleteProject({{ $item->id }})" class="flex-1 text-center px-2 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded transition" title="Hapus Project">
                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    </div>
@empty
    <div class="col-span-full bg-white rounded-lg shadow-md p-8 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
        </svg>
        <p class="text-gray-600 text-lg mb-2">Tidak ada hasil pencarian</p>
        <p class="text-gray-500 text-sm">Coba kata kunci yang berbeda</p>
    </div>
@endforelse
