@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Projects</h1>
            <p class="text-gray-600 mt-2">Kelola semua project dari penawaran yang disetujui</p>
        </div>
        <a href="{{ route('proyek.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Buat Project</span>
        </a>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Total Project</p>
            <p class="text-3xl font-bold text-gray-900 mt-2" id="stat-total">{{ $total_projects ?? 0 }}</p>
            <p class="text-xs text-gray-600 mt-1">Project terdaftar</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Selesai</p>
            <p class="text-3xl font-bold text-green-600 mt-2" id="stat-completed">{{ $completed_projects ?? 0 }}</p>
            <p class="text-xs text-green-600 mt-1">Sudah diselesaikan</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <p class="text-gray-600 text-sm">Berlangsung</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2" id="stat-ongoing">{{ $ongoing_projects ?? 0 }}</p>
            <p class="text-xs text-yellow-600 mt-1">Sedang berjalan</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <p class="text-gray-600 text-sm">Belum Dimulai</p>
            <p class="text-3xl font-bold text-red-600 mt-2" id="stat-unprojected">{{ $unprojected_quotations ?? 0 }}</p>
            <p class="text-xs text-red-600 mt-1">Siap dibuat project</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <input type="text" id="search-projects" placeholder="Cari project atau client..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button onclick="resetSearch()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Reset
            </button>
        </div>
    </div>

    <!-- Project Cards Grid -->
    <div id="projects-container">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="projects-grid">
            @include('proyek.partials.project-grid', compact('proyeks'))
        </div>
    </div>

    <!-- Pagination -->
    @if($proyeks->count() > 0)
        <div class="mt-8" id="pagination-container">
            {{ $proyeks->links() }}
        </div>
    @endif

    @if (session('success'))
        <script>
            showToast("{{ session('success') }}", 'success');
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-projects');
            let searchTimeout;

            // Live search
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();

                searchTimeout = setTimeout(() => {
                    if (query.length > 0) {
                        performSearch(query);
                    } else {
                        resetSearch();
                    }
                }, 300);
            });

            function performSearch(query) {
                fetch(`{{ route('proyek.search') }}?q=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('projects-grid').innerHTML = data.html;
                        document.getElementById('pagination-container').innerHTML = data.pagination;
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            function resetSearch() {
                location.reload();
            }
        });

        function deleteProject(id) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/proyek/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</div>
@endsection
