@extends('layouts.app')

@section('title', 'Finance Dashboard')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Finance Dashboard</h1>
            <p class="text-gray-600 mt-2">Monitor kesehatan keuangan dan budget proyek perusahaan</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('finance.budget') }}" class="px-6 py-3 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span>Lihat Budget</span>
            </a>
            <a href="{{ route('pengeluaran.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Pengeluaran</span>
            </a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Total Budget</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($total_budget, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-600 mt-1">Rencana keseluruhan</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <p class="text-gray-600 text-sm">Total Realisasi</p>
            <p class="text-3xl font-bold text-red-600 mt-2">Rp {{ number_format($total_realisasi, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-600 mt-1">Pengeluaran aktual</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Sisa Budget</p>
            <p class="text-3xl font-bold text-green-600 mt-2">Rp {{ number_format($sisa_budget, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-600 mt-1">Budget tersedia</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm">Penggunaan Budget</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($persentase_penggunaan, 1) }}%</p>
            <p class="text-xs text-gray-600 mt-1">Dari total budget</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Budget Trend Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Trend Budget & Realisasi</h2>
            <p class="text-sm text-gray-600 mb-4">Data 6 bulan terakhir</p>
            <canvas id="budgetTrendChart" height="250"></canvas>
        </div>

        <!-- Expense by Category Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengeluaran per Kategori</h2>
            <p class="text-sm text-gray-600 mb-4">Total pengeluaran berdasarkan kategori</p>
            <canvas id="categoryChart" height="250"></canvas>
        </div>
    </div>

    <!-- Top Projects and Critical Projects -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Top 5 Projects by Budget -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top 5 Proyek Budget Terbesar</h2>
            <div class="space-y-3">
                @forelse($top_proyek as $budget)
                    <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $budget->proyek->nama }}</h3>
                                <p class="text-xs text-gray-600">{{ $budget->proyek->client->nama }}</p>
                            </div>
                            <span class="text-sm font-semibold text-blue-600">Rp {{ number_format($budget->jumlah_rencana, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min($budget->persentase_penggunaan, 100) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($budget->persentase_penggunaan, 1) }}% terpakai</p>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">Belum ada data budget proyek</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Critical Projects (>80% Budget Usage) -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Proyek Budget Kritis (>80%)</h2>
            <div class="space-y-3">
                @forelse($proyek_kritis as $budget)
                    <div class="p-3 border-l-4 border-red-500 bg-red-50 rounded">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $budget->proyek->nama }}</h3>
                                <p class="text-xs text-gray-600">{{ $budget->proyek->client->nama }}</p>
                            </div>
                            <span class="text-sm font-semibold text-red-600">Rp {{ number_format($budget->jumlah_realisasi, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ min($budget->persentase_penggunaan, 100) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($budget->persentase_penggunaan, 1) }}% terpakai - PERHATIAN!</p>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">Tidak ada proyek kritis</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Expenses -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengeluaran Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Proyek</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Kategori</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_pengeluaran as $pengeluaran)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <a href="{{ route('finance.budget.show', $pengeluaran->proyek_id) }}" class="text-blue-600 hover:underline">
                                    {{ $pengeluaran->proyek->nama }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $pengeluaran->kategori)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $pengeluaran->deskripsi }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $pengeluaran->tanggal->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Belum ada pengeluaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Budget Trend Chart
            const budgetCtx = document.getElementById('budgetTrendChart');
            if (budgetCtx) {
                new Chart(budgetCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($bulan_labels) !!},
                        datasets: [
                            {
                                label: 'Budget Rencana',
                                data: {!! json_encode($bulan_budget) !!},
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2
                            },
                            {
                                label: 'Budget Realisasi',
                                data: {!! json_encode($bulan_realisasi) !!},
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart');
            if (categoryCtx) {
                new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($kategori_labels) !!},
                        datasets: [{
                            data: {!! json_encode($kategori_totals) !!},
                            backgroundColor: [
                                '#3b82f6',
                                '#ef4444',
                                '#10b981',
                                '#f59e0b',
                                '#8b5cf6'
                            ],
                            borderColor: '#ffffff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
