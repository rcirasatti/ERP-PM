<!-- Price Trend Chart Component -->
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-gray-900">Tren Harga Material</h2>
        <select id="trendMaterialFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="loadPriceTrend()">
            <option value="">-- Pilih Material --</option>
        </select>
    </div>

    <!-- Chart Container -->
    <div class="relative h-64 bg-gray-50 rounded-lg p-4" id="priceChartContainer">
        <canvas id="priceChart"></canvas>
    </div>

    <!-- Stats Below Chart -->
    <div class="grid grid-cols-4 gap-4 mt-6">
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-xs text-gray-600 uppercase tracking-wider">Harga Tertinggi</p>
            <p class="text-lg font-bold text-blue-600" id="maxPrice">Rp 0</p>
        </div>
        <div class="bg-green-50 rounded-lg p-4">
            <p class="text-xs text-gray-600 uppercase tracking-wider">Harga Terendah</p>
            <p class="text-lg font-bold text-green-600" id="minPrice">Rp 0</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-4">
            <p class="text-xs text-gray-600 uppercase tracking-wider">Rata-rata</p>
            <p class="text-lg font-bold text-purple-600" id="avgPrice">Rp 0</p>
        </div>
        <div class="bg-orange-50 rounded-lg p-4">
            <p class="text-xs text-gray-600 uppercase tracking-wider">Tren</p>
            <p class="text-lg font-bold text-orange-600" id="trendIndicator">📊 Stabil</p>
        </div>
    </div>

    <!-- Detail Table -->
    <div class="mt-6">
        <h3 class="text-sm font-medium text-gray-700 mb-3">Riwayat Harga Terakhir</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Material</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">No. Penawaran</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Harga</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Tanggal</th>
                    </tr>
                </thead>
                <tbody id="priceHistoryTable" class="divide-y divide-gray-200">
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
let priceChart = null;

function loadPriceTrend() {
    const materialId = document.getElementById('trendMaterialFilter')?.value;
    console.log('loadPriceTrend called with materialId:', materialId);

    if (!materialId) {
        // Reset jika tidak ada material dipilih
        document.getElementById('priceChartContainer').innerHTML = 
            '<p class="text-center text-gray-500 py-8">Pilih material untuk melihat tren harga</p>';
        return;
    }

    // Restore canvas element if needed
    const containerDiv = document.getElementById('priceChartContainer');
    if (!document.getElementById('priceChart')) {
        containerDiv.innerHTML = '<div style="position: relative; height: 100%; width: 100%;"><canvas id="priceChart"></canvas></div>';
    }

    // Fetch price trend dari API Phase 1 - hanya butuh material_id
    fetch(`/api/penawaran/item-price-trend?material_id=${materialId}&limit=20`)
        .then(res => res.json())
        .then(data => {
            console.log('API response:', data);
            if (data.success && data.data) {
                console.log('Rendering chart with data:', data.data);
                renderPriceChart(data.data);
                updatePriceStats(data.data);
                updatePriceHistoryTable(data.data);
            } else {
                console.error('Invalid response:', data);
            }
        })
        .catch(err => console.error('Error loading price trend:', err));
}

function renderPriceChart(data) {
    // Handle API response structure
    const history = data.history || [];
    console.log('renderPriceChart - history length:', history.length);
    
    if (!history || history.length === 0) {
        document.getElementById('priceChartContainer').innerHTML = 
            '<p class="text-center text-gray-500 py-8">Tidak ada data harga historis</p>';
        return;
    }

    // Prepare chart data - using 'date' field from API
    const labels = history.map(d => d.date || new Date(d.created_at).toLocaleDateString('id-ID'));
    const prices = history.map(d => parseFloat(d.harga_jual));
    
    console.log('Chart labels:', labels);
    console.log('Chart prices:', prices);

    const ctx = document.getElementById('priceChart');
    
    if (!ctx) {
        console.error('Canvas element priceChart not found!');
        return;
    }
    
    if (priceChart) {
        priceChart.destroy();
    }

    try {
        priceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Harga',
                    data: prices,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                return 'Harga: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
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
        console.log('Chart created successfully!');
    } catch (error) {
        console.error('Error creating chart:', error);
    }
}

function updatePriceStats(data) {
    const history = data.history || [];
    if (!history || history.length === 0) return;

    const prices = history.map(h => parseFloat(h.harga_jual));
    const max = Math.max(...prices);
    const min = Math.min(...prices);
    const avg = prices.reduce((a, b) => a + b, 0) / prices.length;

    // Use trend from stats if available
    const stats = data.stats;
    let trend = '📊 Stabil';
    if (stats && stats.trend) {
        trend = stats.trend === 'increasing' ? '📈 Naik' : 
                stats.trend === 'decreasing' ? '📉 Turun' : '📊 Stabil';
    }

    document.getElementById('maxPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(max));
    document.getElementById('minPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(min));
    document.getElementById('avgPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(avg));
    document.getElementById('trendIndicator').textContent = trend;
}

function updatePriceHistoryTable(data) {
    const tbody = document.getElementById('priceHistoryTable');
    const history = data.history || [];
    
    if (!history || history.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada riwayat harga</td></tr>';
        return;
    }

    const material_nama = data.material_nama || '-';
    tbody.innerHTML = history.slice(0, 10).map(item => `
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 text-gray-900">${material_nama}</td>
            <td class="px-4 py-3 text-gray-600">${item.penawaran_no || '-'}</td>
            <td class="px-4 py-3 font-medium text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(Math.round(item.harga_jual))}</td>
            <td class="px-4 py-3 text-gray-600">${item.date || '-'}</td>
        </tr>
    `).join('');
}

// Initialize material dropdown and load price trend
document.addEventListener('DOMContentLoaded', function() {
    const materialFilter = document.getElementById('trendMaterialFilter');
    const materials = new Map();
    
    // First, try to use global penawaran materials (from show page)
    if (window.penawaranMaterials && window.penawaranMaterials.length > 0) {
        console.log('Using materials from window.penawaranMaterials');
        window.penawaranMaterials.forEach(m => {
            materials.set(m.id, m.name);
        });
    } 
    // Fallback: Extract from form items (for edit/create pages)
    else {
        console.log('Using materials from form items');
        const items = document.querySelectorAll('.item-row');
        
        items.forEach(row => {
            const materialId = row.querySelector('[data-material-id]')?.dataset.materialId ||
                              row.querySelector('.material-id-input')?.value;
            const materialName = row.querySelector('[data-material-name]')?.dataset.materialName ||
                                row.querySelector('[data-nama]')?.dataset.nama ||
                                row.textContent.split('\n')[0];
            
            if (materialId && materialName) {
                materials.set(parseInt(materialId), materialName.trim());
            }
        });
    }
    
    // Populate dropdown
    console.log('Populating dropdown with', materials.size, 'materials');
    if (materials.size > 0) {
        materials.forEach((name, id) => {
            const option = document.createElement('option');
            option.value = id;
            option.textContent = name;
            materialFilter?.appendChild(option);
        });
        
        // Select first material and load price trend
        const firstOption = materialFilter.querySelector('option:nth-of-type(2)');
        if (firstOption) {
            materialFilter.value = firstOption.value;
            console.log('Loading price trend for material:', firstOption.value);
            setTimeout(() => loadPriceTrend(), 100);
        }
    } else {
        console.log('No materials found');
        document.getElementById('priceChartContainer').innerHTML = 
            '<p class="text-center text-gray-500 py-8">Tidak ada material di penawaran ini</p>';
    }
});
</script>
