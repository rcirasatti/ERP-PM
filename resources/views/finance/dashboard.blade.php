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
            <a href="{{ route('finance.pengeluaran') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center space-x-2">
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
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $budget->getStatusColor() }}">
                                {{ number_format($budget->persentase_penggunaan, 1) }}%
                            </span>
                        </div>
                        <div class="flex justify-between text-xs mt-2">
                            <span class="text-gray-600">Budget: Rp {{ number_format($budget->jumlah_rencana, 0, ',', '.') }}</span>
                            <span class="text-red-600 font-medium">Sisa: Rp {{ number_format($budget->sisa_budget, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ min($budget->persentase_penggunaan, 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-600 font-medium">Semua proyek dalam kondisi aman</p>
                        <p class="text-sm text-gray-500">Tidak ada proyek dengan budget kritis</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Expenses -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Pengeluaran Terbaru</h2>
            <a href="{{ route('finance.pengeluaran') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Proyek</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pengeluaran_terbaru as $pengeluaran)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $pengeluaran->tanggal->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $pengeluaran->proyek->nama }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $pengeluaran->getKategoriColor() }}">
                                    {{ $pengeluaran->getKategoriLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-600 max-w-xs">{{ Str::limit($pengeluaran->deskripsi, 50) }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-semibold text-red-600">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada pengeluaran tercatat</td>
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
    // Budget Trend Chart
    const budgetTrendCtx = document.getElementById('budgetTrendChart').getContext('2d');
    new Chart(budgetTrendCtx, {
        type: 'line',
        data: {
            labels: @json($bulan_labels),
            datasets: [{
                label: 'Budget',
                data: @json($bulan_budget),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Realisasi',
                data: @json($bulan_realisasi),
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                        }
                    }
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: @json($pengeluaran_per_kategori->pluck('label')),
            datasets: [{
                data: @json($pengeluaran_per_kategori->pluck('total')),
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)',
                    'rgb(251, 191, 36)',
                    'rgb(168, 85, 247)',
                    'rgb(156, 163, 175)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
