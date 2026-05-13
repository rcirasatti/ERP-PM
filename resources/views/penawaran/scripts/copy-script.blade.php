<!-- Copy Penawaran Script -->
<script>
// Global variables
let similarPenawaran = [];

/**
 * Initialize copy modal - load similar penawaran from same client
 */
function initCopyModal() {
    // Use penawaranClientId and penawaranId from show.blade.php
    const clientId = typeof penawaranClientId !== 'undefined' ? penawaranClientId : document.getElementById('clientId')?.value;
    const currentPenawaranId = typeof penawaranId !== 'undefined' ? penawaranId : document.getElementById('targetPenawaranId')?.value;

    if (!clientId) {
        console.error('clientId not found. penawaranClientId:', penawaranClientId, 'document.getElementById:', document.getElementById('clientId'));
        return;
    }

    // Fetch similar penawaran using Phase 1 API
    fetch(`/api/penawaran/similar?client_id=${clientId}&limit=10&exclude_penawaran_id=${currentPenawaranId}`)
        .then(res => res.json())
        .then(data => {
            similarPenawaran = data.penawaran || [];
            const select = document.getElementById('sourcePenawaran');
            
            // Clear existing options
            select.innerHTML = '<option value="">-- Pilih Penawaran --</option>';
            
            // Add penawaran options
            if (similarPenawaran.length === 0) {
                select.innerHTML = '<option value="" disabled>Tidak ada penawaran sebelumnya</option>';
            } else {
                similarPenawaran.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.id;
                    option.textContent = `${p.no_penawaran} - Rp ${new Intl.NumberFormat('id-ID').format(p.grand_total_with_ppn)} (${new Date(p.created_at).toLocaleDateString('id-ID')})`;
                    select.appendChild(option);
                });
            }
        })
        .catch(err => console.error('Error loading similar penawaran:', err));

    // Also load material options for price trend filter
    loadMaterialOptions();
}

/**
 * Load material options for trend chart filter
 */
function loadMaterialOptions() {
    // Use global penawaran materials already exposed from show page
    if (!window.penawaranMaterials || window.penawaranMaterials.length === 0) {
        console.log('No materials found in window.penawaranMaterials');
        return;
    }

    const filter = document.getElementById('trendMaterialFilter');
    if (!filter) return;

    // Add materials to filter
    window.penawaranMaterials.forEach(m => {
        // Check if already exists
        const exists = Array.from(filter.options).some(opt => opt.value == m.id);
        if (!exists) {
            const option = document.createElement('option');
            option.value = m.id;
            option.textContent = m.name;
            filter.appendChild(option);
        }
    });

    console.log('Materials loaded into filter:', window.penawaranMaterials.length);
}

/**
 * Show copy modal and initialize
 */
function showCopyModal() {
    document.getElementById('copyModal').classList.remove('hidden');
    initCopyModal();
}

/**
 * Close copy modal
 */
function closeCopyModal() {
    document.getElementById('copyModal').classList.add('hidden');
    document.getElementById('copyForm').reset();
}

/**
 * Load preview items when source penawaran is selected
 */
function loadSourceItems() {
    const sourceId = document.getElementById('sourcePenawaran')?.value;

    if (!sourceId) {
        document.getElementById('previewItems').innerHTML = 
            '<p class="text-sm text-gray-500">Pilih penawaran sumber terlebih dahulu</p>';
        return;
    }

    // Find selected penawaran
    const selected = similarPenawaran.find(p => p.id == sourceId);
    
    if (!selected || !selected.items) {
        document.getElementById('previewItems').innerHTML = 
            '<p class="text-sm text-gray-500">Tidak ada item dalam penawaran ini</p>';
        return;
    }

    // Display items
    const preview = selected.items.map((item, idx) => `
        <div class="flex justify-between items-center text-sm border-b pb-2">
            <div>
                <p class="font-medium text-gray-900">${idx + 1}. ${item.material_nama}</p>
                <p class="text-xs text-gray-500">${item.nama_satuan} × ${item.jumlah}</p>
            </div>
            <p class="font-semibold text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(item.harga_jual)}</p>
        </div>
    `).join('');

    document.getElementById('previewItems').innerHTML = preview;
    
    // Enable submit button
    document.getElementById('copySubmitBtn').disabled = false;
}

/**
 * Submit copy request
 */
async function submitCopy() {
    const sourceId = document.getElementById('sourcePenawaran')?.value;
    const targetId = document.getElementById('targetPenawaranId')?.value;
    const strategy = document.querySelector('input[name="price_strategy"]:checked')?.value;

    if (!sourceId || !targetId || !strategy) {
        showErrorAlert('Lengkapi semua field terlebih dahulu');
        return;
    }

    // Build override prices if strategy is override
    let overridePrices = [];
    if (strategy === 'override') {
        // Get user input for each item
        const selected = similarPenawaran.find(p => p.id == sourceId);
        if (!selected || !selected.items) return;

        for (const item of selected.items) {
            const priceInput = prompt(`Harga untuk ${item.material_nama}:`, item.harga_jual);
            if (priceInput === null) return; // User cancelled
            overridePrices.push({
                item_id: item.id,
                harga_asli: parseFloat(priceInput) || item.harga_jual,
                persentase_margin: item.margin || 0
            });
        }
    }

    try {
        const response = await fetch('/api/penawaran/copy-items', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                source_penawaran_id: parseInt(sourceId),
                target_penawaran_id: parseInt(targetId),
                price_strategy: strategy,
                override_prices: overridePrices
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Gagal copy items');
        }

        // Success
        const copiedCount = data.data?.items_copied?.length || data.copied_count || 0;
        showSuccessAlert(`✅ Berhasil! ${copiedCount} item dicopy`);
        closeCopyModal();
        
        // Reload page to show new items
        setTimeout(() => location.reload(), 1500);

    } catch (error) {
        console.error('Copy error:', error);
        showErrorAlert('❌ Error: ' + error.message);
    }
}

/**
 * Show similar penawaran from same client in a dropdown
 */
function showSimilarPenawaran() {
    showCopyModal();
}

/**
 * Utility: format currency
 */
function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
}

/**
 * Utility: show success alert
 */
function showSuccessAlert(message) {
    const alert = document.createElement('div');
    alert.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-pulse';
    alert.textContent = message;
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 3000);
}

/**
 * Utility: show error alert
 */
function showErrorAlert(message) {
    const alert = document.createElement('div');
    alert.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    alert.textContent = message;
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 4000);
}

/**
 * Close modal on escape key
 */
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCopyModal();
    }
});

/**
 * Handle source penawaran selection change
 */
document.addEventListener('DOMContentLoaded', function() {
    const sourceSelect = document.getElementById('sourcePenawaran');
    if (sourceSelect) {
        sourceSelect.addEventListener('change', loadSourceItems);
    }
});
</script>
