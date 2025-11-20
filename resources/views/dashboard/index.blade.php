@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back! Here's your project overview for {{ date('F Y') }}.</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Projects -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Projects</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalProyek }}</p>
                    <p class="text-blue-600 text-xs mt-2">
                        ℹ {{ $proyekCompleted }} Completed
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">In Progress</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $proyekInProgress }}</p>
                    <p class="text-yellow-600 text-xs mt-2">
                        Active projects
                    </p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Quotations -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending Quotations</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingPenawaran }}</p>
                    <p class="text-purple-600 text-xs mt-2">
                        Awaiting approval
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2 break-words">
                        @if($totalRevenue >= 1000000000)
                            Rp {{ number_format($totalRevenue / 1000000000, 1, ',', '.') }}M
                        @elseif($totalRevenue >= 1000000)
                            Rp {{ number_format($totalRevenue / 1000000, 1, ',', '.') }}Jt
                        @else
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        @endif
                    </p>
                    <p class="text-green-600 text-xs mt-2">
                        From approved quotations
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg flex-shrink-0">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Projects (spans 2 columns) -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent Projects</h2>
                <a href="{{ route('proyek.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Project Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Progress</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentProyek as $proyek)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $proyek->nama }}</p>
                                        <p class="text-xs text-gray-600">{{ Str::limit($proyek->deskripsi, 50) }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $proyek->client->nama ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if(in_array($proyek->status, ['survei', 'instalasi', 'pengujian']))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">In Progress</span>
                                    @elseif($proyek->status === 'selesai')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                                    @elseif($proyek->status === 'baru')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">New</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($proyek->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-24 h-2 bg-gray-200 rounded-full">
                                            @php
                                                $progress = $proyek->persentase_progres ?? 0;
                                                $bgColor = $progress >= 100 ? 'bg-green-600' : ($progress >= 75 ? 'bg-blue-600' : ($progress >= 50 ? 'bg-yellow-600' : 'bg-red-600'));
                                            @endphp
                                            <div class="{{ $bgColor }} h-2 rounded-full" style="width: {{ min($progress, 100) }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 w-10">{{ (int)$progress }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-600">
                                    No projects found. <a href="{{ route('proyek.create') }}" class="text-blue-600 hover:underline">Create one</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Project Status Distribution -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Distribution</h2>

            <div class="space-y-4">
                @php
                    $statusColors = [
                        'survei' => ['color' => 'blue', 'label' => 'In Progress'],
                        'instalasi' => ['color' => 'blue', 'label' => 'In Progress'],
                        'pengujian' => ['color' => 'blue', 'label' => 'In Progress'],
                        'selesai' => ['color' => 'green', 'label' => 'Completed'],
                        'baru' => ['color' => 'yellow', 'label' => 'New'],
                        'bast' => ['color' => 'purple', 'label' => 'BAST'],
                    ];
                    $totalForPercentage = $proyekByStatus->sum('count') ?: 1;
                @endphp

                @php
                    // Group status for display
                    $inProgressCount = $proyekByStatus->whereIn('status', ['survei', 'instalasi', 'pengujian'])->sum('count');
                    $completedCount = $proyekByStatus->where('status', 'selesai')->first()?->count ?? 0;
                    $newCount = $proyekByStatus->where('status', 'baru')->first()?->count ?? 0;
                @endphp

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">In Progress</span>
                        <span class="text-sm font-bold text-gray-900">{{ $inProgressCount }} ({{ round(($inProgressCount / $totalForPercentage) * 100) }}%)</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-blue-600 rounded-full" style="width: {{ round(($inProgressCount / $totalForPercentage) * 100) }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Completed</span>
                        <span class="text-sm font-bold text-gray-900">{{ $completedCount }} ({{ round(($completedCount / $totalForPercentage) * 100) }}%)</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-green-600 rounded-full" style="width: {{ round(($completedCount / $totalForPercentage) * 100) }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">New</span>
                        <span class="text-sm font-bold text-gray-900">{{ $newCount }} ({{ round(($newCount / $totalForPercentage) * 100) }}%)</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-yellow-600 rounded-full" style="width: {{ round(($newCount / $totalForPercentage) * 100) }}%"></div>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-gray-200">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Avg. Duration</span>
                            <span class="font-semibold text-gray-900">{{ $avgProjectDuration }} days</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">On-Time Delivery</span>
                            <span class="font-semibold text-green-600">{{ $onTimePercentage }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Budget Variance</span>
                            <span class="font-semibold {{ $budgetVariance > 5 ? 'text-red-600' : 'text-yellow-600' }}">
                                {{ $budgetVariance > 0 ? '+' : '' }}{{ $budgetVariance }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks & Expenses -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Tasks -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Incomplete Tasks</h2>
                <a href="{{ route('proyek.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($upcomingTugas as $tugas)
                    <div class="px-6 py-4 hover:bg-gray-50 transition cursor-pointer">
                        <div class="flex items-start space-x-4">
                            <div class="mt-1">
                                @if($tugas->selesai)
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $tugas->nama }}</p>
                                <p class="text-sm text-gray-600 mt-1">Project: 
                                    <a href="{{ route('proyek.show', $tugas->proyek) }}" class="text-blue-600 hover:underline">
                                        {{ $tugas->proyek->nama }}
                                    </a>
                                </p>
                                <div class="flex items-center space-x-2 mt-2">
                                    @if($tugas->selesai)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Completed</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">In Progress</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-600">
                        No tasks found. Great job! All tasks are completed.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Expenses & Summary -->
        <div class="space-y-6">
            <!-- Expense Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Expense Summary</h2>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
                        <span class="text-sm font-medium text-red-900">Total Expenses</span>
                        <span class="font-bold text-red-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                        <span class="text-sm font-medium text-blue-900">Active Projects</span>
                        <span class="font-bold text-blue-600">{{ $proyekInProgress }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
                        <span class="text-sm font-medium text-green-900">Total Clients</span>
                        <span class="font-bold text-green-600">{{ $totalClients }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                        <span class="text-sm font-medium text-yellow-900">Low Stock Items</span>
                        <span class="font-bold text-yellow-600">{{ $lowStockItems }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Expenses List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Expenses</h2>
                    <a href="{{ route('pengeluaran.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>

                <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                    @forelse($recentPengeluaran as $pengeluaran)
                        <div class="px-6 py-3 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $pengeluaran->deskripsi }}</p>
                                    <p class="text-xs text-gray-600">{{ $pengeluaran->tanggal->format('d M Y') }}</p>
                                </div>
                                <span class="font-semibold text-red-600">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-600">
                            No expenses recorded yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
