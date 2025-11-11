@extends('layouts.app')

@section('title', 'Detail Project')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb & Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-2 text-gray-600 mb-4">
                <a href="{{ route('proyek.index') }}" class="hover:text-blue-600">Project</a>
                <span>/</span>
                <span class="text-gray-900">{{ $proyek->nama }}</span>
            </div>

            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $proyek->nama }}</h1>
                    <p class="text-gray-600 mt-2">Dibuat pada
                        {{ $proyek->created_at ? \Carbon\Carbon::parse($proyek->created_at)->format('d M Y H:i') : '-' }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('proyek.edit', $proyek->id) }}"
                        class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        <span>Edit</span>
                    </a>
                    <a href="{{ route('proyek.index') }}"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Status & Client Info -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $proyek->getStatusColor() }}">
                        {{ $proyek->getStatusLabel() }}
                    </span>
                    <span class="text-gray-600">Client: <strong>{{ $proyek->client->nama ?? 'N/A' }}</strong></span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Project Info Card -->
            <div class="col-span-2 space-y-6">
                <!-- Main Progress Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Progress Project</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Progress Keseluruhan</span>
                            <span class="text-3xl font-bold text-gray-900"
                                id="project-progress">{{ number_format($proyek->persentase_progres, 0) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-6">
                            <div class="h-6 rounded-full {{ $proyek->getProgressColor() }} transition-all duration-300"
                                id="project-progress-bar" style="width: {{ $proyek->persentase_progres }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Project Details Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Informasi Project</h3>
                    <div class="grid grid-cols-2 gap-6">
                        @if ($proyek->lokasi)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Lokasi</p>
                                <p class="font-medium text-gray-900">{{ $proyek->lokasi }}</p>
                            </div>
                        @endif
                        @if ($proyek->tanggal_mulai)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Tanggal Mulai</p>
                                <p class="font-medium text-gray-900">{{ $proyek->tanggal_mulai->format('d M Y') }}</p>
                            </div>
                        @endif
                        @if ($proyek->tanggal_selesai)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Target Selesai</p>
                                <p class="font-medium text-gray-900">{{ $proyek->tanggal_selesai->format('d M Y') }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600 mb-1">No. Penawaran</p>
                            <p class="font-medium text-gray-900">{{ $proyek->penawaran->no_penawaran ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if ($proyek->deskripsi)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-2">Deskripsi</p>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $proyek->deskripsi }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stats Card -->
            <div class="space-y-6">
                <!-- Budget Card -->
                @php
                    $budget = $proyek->budget()->first();
                @endphp
                @if ($budget)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Budget Project</h3>
                            <a href="{{ route('finance.dashboard') }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Detail →
                            </a>
                        </div>
                        <div class="space-y-4">
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <p class="text-sm text-gray-600 mb-1">Budget Rencana</p>
                                <p class="text-xl font-bold text-blue-600">Rp
                                    {{ number_format($budget->jumlah_rencana, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-4 bg-red-50 rounded-lg border border-red-100">
                                <p class="text-sm text-gray-600 mb-1">Realisasi</p>
                                <p class="text-xl font-bold text-red-600">Rp
                                    {{ number_format($budget->jumlah_realisasi, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                                <p class="text-sm text-gray-600 mb-1">Sisa Budget</p>
                                <p class="text-xl font-bold text-green-600">Rp
                                    {{ number_format($budget->sisa_budget, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700">Penggunaan Budget</span>
                                    <span
                                        class="text-sm font-bold text-gray-900">{{ number_format($budget->persentase_penggunaan, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="h-3 rounded-full {{ $budget->persentase_penggunaan < 70 ? 'bg-green-500' : ($budget->persentase_penggunaan < 90 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                        style="width: {{ min($budget->persentase_penggunaan, 100) }}%"></div>
                                </div>
                                <div class="mt-2 text-center">
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full {{ $budget->getStatusColor() }}">
                                        @if ($budget->getStatusBudget() == 'aman')
                                            Status: Aman
                                        @elseif($budget->getStatusBudget() == 'peringatan')
                                            Status: Peringatan
                                        @else
                                            Status: Bahaya
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <!-- Task Stats Section (Horizontal) -->
        <div class="mt-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-600 mb-2">Total Task</p>
                    <p class="text-4xl font-bold text-blue-600">{{ $proyek->tugas()->count() }}</p>
                    <p class="text-xs text-gray-500 mt-2">Semua task</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-600 mb-2">Selesai</p>
                    <p class="text-4xl font-bold text-green-600">{{ $proyek->tugas()->where('selesai', true)->count() }}</p>
                    <p class="text-xs text-gray-500 mt-2">Task yang sudah selesai</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-600 mb-2">Belum Selesai</p>
                    <p class="text-4xl font-bold text-yellow-600">{{ $proyek->tugas()->where('selesai', false)->count() }}</p>
                    <p class="text-xs text-gray-500 mt-2">Task yang belum selesai</p>
                </div>
            </div>
        </div>

        <!-- Tasks Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Task-Task Project</h2>
            </div>

            <!-- Add Task Form (Inline) -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tambah Task Baru</h3>
                <form id="addTaskForm" class="flex gap-3">
                    @csrf
                    <input type="text" id="taskNameInput" name="nama" placeholder="Nama task..."
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <span>Tambah</span>
                    </button>
                </form>
            </div>

            <!-- Search and Filter -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                <div class="flex flex-col md:flex-row gap-3">
                    <input type="text" id="taskSearchInput" placeholder="Cari task..."
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button onclick="resetTaskSearch()"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Reset
                    </button>
                </div>
            </div>

            <!-- Task List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden" id="taskListContainer">
                @if ($proyek->tugas->isEmpty())
                    <div class="p-12 text-center" id="emptyState">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">Belum ada task</p>
                        <p class="text-gray-400 mt-2">Tambahkan task pertama untuk project ini</p>
                    </div>
                @else
                    <div class="space-y-6 p-6" id="taskList">
                        <!-- Belum Selesai Section -->
                        <div id="pendingTasksSection">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">
                                    <span
                                        id="pendingCount">{{ $proyek->tugas()->where('selesai', false)->count() }}</span>
                                </span>
                                Belum Selesai
                            </h4>
                            <div class="divide-y divide-gray-200 border border-yellow-200 rounded-lg overflow-hidden"
                                id="pendingTasksList">
                                @forelse($proyek->tugas->where('selesai', false) as $tugas)
                                    <div class="hover:bg-gray-50 transition p-4" data-task-id="{{ $tugas->id }}"
                                        data-task-name="{{ strtolower($tugas->nama) }}"
                                        data-task-search="{{ strtolower($tugas->nama) }}">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                                <input type="checkbox" id="task-{{ $tugas->id }}"
                                                    class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer task-checkbox shrink-0"
                                                    data-task-id="{{ $tugas->id }}"
                                                    data-proyek-id="{{ $proyek->id }}"
                                                    onchange="toggleTaskStatus(this)">
                                                <label for="task-{{ $tugas->id }}"
                                                    class="flex-1 cursor-pointer min-w-0">
                                                    <span class="font-medium text-gray-900 task-name">
                                                        {{ $tugas->nama }}
                                                    </span>
                                                </label>
                                            </div>
                                            <button type="button"
                                                onclick="deleteTask({{ $tugas->id }}, {{ $proyek->id }})"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Hapus Task">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-gray-500 text-sm">
                                        Semua task sudah selesai! 🎉
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Sudah Selesai Section -->
                        <div id="completedTasksSection" class="pt-6 border-t border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-800 rounded-full text-sm font-bold">
                                    <span
                                        id="completedCount">{{ $proyek->tugas()->where('selesai', true)->count() }}</span>
                                </span>
                                Sudah Selesai
                            </h4>
                            <div class="divide-y divide-gray-200 border border-green-200 rounded-lg overflow-hidden"
                                id="completedTasksList">
                                @forelse($proyek->tugas->where('selesai', true) as $tugas)
                                    <div class="hover:bg-gray-50 transition p-4" data-task-id="{{ $tugas->id }}"
                                        data-task-name="{{ strtolower($tugas->nama) }}"
                                        data-task-search="{{ strtolower($tugas->nama) }}">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                                <input type="checkbox" id="task-{{ $tugas->id }}"
                                                    class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer task-checkbox shrink-0"
                                                    data-task-id="{{ $tugas->id }}"
                                                    data-proyek-id="{{ $proyek->id }}" checked
                                                    onchange="toggleTaskStatus(this)">
                                                <label for="task-{{ $tugas->id }}"
                                                    class="flex-1 cursor-pointer min-w-0">
                                                    <span
                                                        class="font-medium text-gray-900 task-name line-through text-gray-500">
                                                        {{ $tugas->nama }}
                                                    </span>
                                                </label>
                                            </div>
                                            <button type="button" disabled
                                                class="p-2 text-gray-400 cursor-not-allowed rounded-lg transition"
                                                title="Task selesai tidak dapat dihapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-gray-500 text-sm">
                                        Belum ada task yang selesai
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <script>
            // Helper function to safely get CSRF token
            function getCsrfToken() {
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                if (!token) {
                    console.error('CSRF token not found in meta tag!');
                    throw new Error('CSRF token not available');
                }
                return token;
            }

            // Add Task via AJAX
            document.getElementById('addTaskForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const input = document.getElementById('taskNameInput');
                const taskName = input.value.trim();

                if (!taskName) {
                    showToast('Nama task tidak boleh kosong', 'error');
                    return;
                }

                // Disable form while submitting
                input.disabled = true;
                form.querySelector('button').disabled = true;

                fetch('/proyek/{{ $proyek->id }}/tugas', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            nama: taskName
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Clear input
                            input.value = '';

                            // Hide empty state if exists
                            const emptyState = document.getElementById('emptyState');
                            if (emptyState) {
                                emptyState.remove();
                            }

                            // Get or create task list
                            let taskList = document.getElementById('taskList');
                            if (!taskList) {
                                const container = document.getElementById('taskListContainer');
                                taskList = document.createElement('div');
                                taskList.id = 'taskList';
                                taskList.className = 'divide-y divide-gray-200';
                                container.appendChild(taskList);
                            }

                            // Add new task to list
                            const taskHtml = `
                    <div class="hover:bg-gray-50 transition p-6" data-task-id="${data.task.id}">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <input type="checkbox" 
                                       id="task-${data.task.id}" 
                                       class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer task-checkbox shrink-0"
                                       data-task-id="${data.task.id}"
                                       data-proyek-id="{{ $proyek->id }}"
                                       onchange="toggleTaskStatus(this)">
                                <label for="task-${data.task.id}" class="flex-1 cursor-pointer min-w-0">
                                    <span class="font-medium text-gray-900 task-name">
                                        ${data.task.nama}
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center gap-4 shrink-0">
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full task-status bg-yellow-100 text-yellow-800">
                                    Belum Selesai
                                </span>
                                <button type="button" 
                                        onclick="deleteTask(${data.task.id}, {{ $proyek->id }})"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus Task">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                            taskList.insertAdjacentHTML('beforeend', taskHtml);

                            // Update stats
                            updateStats(data.progress);

                            showToast(data.message, 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Gagal menambahkan task', 'error');
                    })
                    .finally(() => {
                        input.disabled = false;
                        form.querySelector('button').disabled = false;
                        input.focus();
                    });
            });

            // Toggle Task Status
            function toggleTaskStatus(checkbox) {
                const taskId = checkbox.dataset.taskId;
                const proyekId = checkbox.dataset.proyekId;
                const isCompleted = checkbox.checked;

                console.log('Toggling task:', taskId, 'Project:', proyekId, 'Status:', isCompleted);

                // Find the task row with data-task-id
                let taskRow = checkbox.closest('div[data-task-id]');
                if (!taskRow) {
                    console.error('Task row not found. Checkbox:', checkbox);
                    checkbox.checked = !isCompleted;
                    return;
                }

                console.log('Task row found. Moving task between sections...');

                // Get the label for visual update
                const label = taskRow.querySelector('span.task-name');
                if (!label) {
                    console.error('Task name span not found');
                    checkbox.checked = !isCompleted;
                    return;
                }

                // Update visual classes
                if (isCompleted) {
                    label.classList.add('line-through', 'text-gray-500');
                    label.classList.remove('text-gray-900');
                } else {
                    label.classList.remove('line-through', 'text-gray-500');
                    label.classList.add('text-gray-900');
                }

                // Disable checkbox while sending
                checkbox.disabled = true;

                const url = `/proyek/${proyekId}/tugas/${taskId}/status`;
                console.log('Calling API:', url);

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            selesai: isCompleted
                        })
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            // Move task to appropriate section
                            const pendingTasksList = document.getElementById('pendingTasksList');
                            const completedTasksList = document.getElementById('completedTasksList');

                            if (isCompleted && completedTasksList) {
                                // Move to completed section
                                completedTasksList.appendChild(taskRow);
                            } else if (!isCompleted && pendingTasksList) {
                                // Move to pending section
                                pendingTasksList.appendChild(taskRow);
                            }

                            // Update counters
                            updateTaskCounters();

                            // Update stats
                            updateStats(data.progress);

                            showToast(data.message, 'success');
                        } else {
                            // Revert on error
                            checkbox.checked = !isCompleted;
                            label.classList.remove('line-through', 'text-gray-500');
                            label.classList.add('text-gray-900');
                            showToast(data.message || 'Gagal mengupdate status task', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert on error
                        checkbox.checked = !isCompleted;
                        label.classList.remove('line-through', 'text-gray-500');
                        label.classList.add('text-gray-900');
                        showToast('Gagal mengupdate status task: ' + error.message, 'error');
                    })
                    .finally(() => {
                        checkbox.disabled = false;
                    });
            }

            // Update task counters for grouped sections
            function updateTaskCounters() {
                const pendingRows = document.querySelectorAll('#pendingTasksList > div[data-task-name]');
                const completedRows = document.querySelectorAll('#completedTasksList > div[data-task-name]');

                const pendingCount = document.getElementById('pendingCount');
                const completedCount = document.getElementById('completedCount');

                if (pendingCount) pendingCount.textContent = pendingRows.length;
                if (completedCount) completedCount.textContent = completedRows.length;
            }

            // Delete Task
            function deleteTask(taskId, proyekId) {
                console.log('deleteTask called. TaskID:', taskId, 'ProyekID:', proyekId);

                // Find the task row
                const taskRow = document.querySelector(`[data-task-id="${taskId}"]`);
                if (!taskRow) {
                    console.error('Task row not found for ID:', taskId);
                    showToast('Task tidak ditemukan', 'error');
                    return;
                }

                // Get task name for confirmation message
                const taskName = taskRow.querySelector('span.task-name')?.textContent || 'Task';

                // Show custom confirmation modal instead of browser confirm
                showConfirm(
                    `Apakah Anda yakin ingin menghapus task "${taskName}"? Tindakan ini tidak dapat dibatalkan.`,
                    'Hapus Task',
                    function() {
                        performDeleteTask(taskId, proyekId, taskRow);
                    }
                );
            }

            // Perform the actual delete operation
            function performDeleteTask(taskId, proyekId, taskRow) {
                console.log('Performing delete for TaskID:', taskId);

                // Find and disable the delete button
                const deleteButton = taskRow.querySelector('button[onclick*="deleteTask"]');
                if (deleteButton) {
                    deleteButton.disabled = true;
                    deleteButton.style.opacity = '0.5';
                }

                const url = `/proyek/${proyekId}/tugas/${taskId}`;
                console.log('Calling DELETE:', url);

                fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        console.log('Delete response status:', response.status, 'OK:', response.ok);
                        console.log('Response headers:', response.headers.get('content-type'));

                        // Always try to parse JSON regardless of status
                        return response.json().then(data => {
                            console.log('Parsed response data:', data);

                            // If response was not ok, throw error with message from response
                            if (!response.ok) {
                                throw new Error(data.message || `HTTP error! status: ${response.status}`);
                            }

                            return data;
                        }).catch(err => {
                            console.error('Failed to parse response:', err);
                            throw new Error(`Failed to parse server response: ${err.message}`);
                        });
                    })
                    .then(data => {
                        console.log('Delete successful. Data:', data);
                        if (data.success) {
                            // Remove task from DOM with animation
                            if (taskRow) {
                                taskRow.style.opacity = '0';
                                taskRow.style.transition = 'opacity 0.3s ease-out';
                                setTimeout(() => {
                                    taskRow.remove();

                                    // Check if no more tasks
                                    const taskList = document.getElementById('taskList');
                                    if (taskList && taskList.children.length === 0) {
                                        const container = document.getElementById('taskListContainer');
                                        container.innerHTML = `
                                <div class="p-12 text-center" id="emptyState">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">Belum ada task</p>
                                    <p class="text-gray-400 mt-2">Tambahkan task pertama untuk project ini</p>
                                </div>
                            `;
                                    }
                                }, 300);
                            }

                            // Recalculate stats from DOM (more accurate after deletion)
                            updateStatsFromDOM();

                            showToast(data.message, 'success');
                        } else {
                            showToast(data.message || data.error || 'Gagal menghapus task', 'error');
                            if (deleteButton) {
                                deleteButton.disabled = false;
                                deleteButton.style.opacity = '1';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Delete error:', error);
                        showToast('Gagal menghapus task: ' + error.message, 'error');
                        if (deleteButton) {
                            deleteButton.disabled = false;
                            deleteButton.style.opacity = '1';
                        }
                    });
            }

            // Update Progress Stats
            function updateStats(progress) {
                // Update progress bar and percentage
                const progressElement = document.getElementById('project-progress');
                const progressBar = document.getElementById('project-progress-bar');

                if (progressElement && progress !== undefined) {
                    progressElement.textContent = Math.round(progress) + '%';
                }
                if (progressBar && progress !== undefined) {
                    progressBar.style.width = progress + '%';

                    // Update color based on progress
                    progressBar.className = 'h-6 rounded-full transition-all duration-300';
                    if (progress === 100) {
                        progressBar.classList.add('bg-green-600');
                    } else if (progress >= 50) {
                        progressBar.classList.add('bg-blue-600');
                    } else if (progress > 0) {
                        progressBar.classList.add('bg-yellow-600');
                    } else {
                        progressBar.classList.add('bg-gray-300');
                    }
                }

                // Count and update task stats
                const allCheckboxes = document.querySelectorAll('.task-checkbox');
                const completedCheckboxes = Array.from(allCheckboxes).filter(cb => cb.checked);
                const pendingCheckboxes = Array.from(allCheckboxes).filter(cb => !cb.checked);

                // Update stat cards if they exist
                const totalTaskEl = document.querySelector('.text-blue-600');
                const completedTaskEl = document.querySelector('.text-green-600');
                const pendingTaskEl = document.querySelector('.text-yellow-600');

                if (totalTaskEl) totalTaskEl.textContent = allCheckboxes.length;
                if (completedTaskEl) completedTaskEl.textContent = completedCheckboxes.length;
                if (pendingTaskEl) pendingTaskEl.textContent = pendingCheckboxes.length;
            }

            // Recalculate stats from DOM (useful after delete when server response might be stale)
            function updateStatsFromDOM() {
                const allCheckboxes = document.querySelectorAll('.task-checkbox');

                if (allCheckboxes.length === 0) {
                    // No tasks left
                    updateStats(0);
                    return;
                }

                const completedCheckboxes = Array.from(allCheckboxes).filter(cb => cb.checked);
                const progress = (completedCheckboxes.length / allCheckboxes.length) * 100;

                console.log('Recalculated from DOM:', {
                    total: allCheckboxes.length,
                    completed: completedCheckboxes.length,
                    progress: progress
                });

                updateStats(progress);
            }

            // Search/Filter functionality
            function filterTasks(searchTerm) {
                const searchLower = searchTerm.toLowerCase().trim();

                // Get all task rows
                const pendingRows = document.querySelectorAll('#pendingTasksList > div[data-task-search]');
                const completedRows = document.querySelectorAll('#completedTasksList > div[data-task-search]');

                let visiblePending = 0;
                let visibleCompleted = 0;

                // Filter pending tasks
                pendingRows.forEach(row => {
                    const taskName = row.getAttribute('data-task-search') || '';
                    const matches = searchLower === '' || taskName.includes(searchLower);
                    row.style.display = matches ? '' : 'none';
                    if (matches) visiblePending++;
                });

                // Filter completed tasks
                completedRows.forEach(row => {
                    const taskName = row.getAttribute('data-task-search') || '';
                    const matches = searchLower === '' || taskName.includes(searchLower);
                    row.style.display = matches ? '' : 'none';
                    if (matches) visibleCompleted++;
                });

                // Update counters to show visible count
                const pendingCount = document.getElementById('pendingCount');
                const completedCount = document.getElementById('completedCount');

                if (pendingCount) {
                    if (searchLower === '') {
                        pendingCount.textContent = pendingRows.length;
                    } else {
                        pendingCount.innerHTML = `<span class="text-blue-600">${visiblePending}</span>/${pendingRows.length}`;
                    }
                }

                if (completedCount) {
                    if (searchLower === '') {
                        completedCount.textContent = completedRows.length;
                    } else {
                        completedCount.innerHTML =
                            `<span class="text-blue-600">${visibleCompleted}</span>/${completedRows.length}`;
                    }
                }
            }

            // Reset search/filter
            function resetTaskSearch() {
                const searchInput = document.getElementById('taskSearchInput');
                if (searchInput) {
                    searchInput.value = '';
                    filterTasks('');
                }
            }

            // Initialize search listener
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('taskSearchInput');
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        filterTasks(this.value);
                    });
                }
            });
        </script>
    </div>
@endsection
