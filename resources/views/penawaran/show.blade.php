@extends('layouts.app')

@section('title', 'Detail Penawaran')

@section('content')
    @if(!$penawaran || !$penawaran->id)
        <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M6.343 3.665c-1.946.777-3.471 2.783-3.471 5.335 0 3.314 2.686 6 6 6s6-2.686 6-6c0-2.552-1.525-4.558-3.471-5.335m0 16c1.946-.777 3.471-2.783 3.471-5.335"></path>
            </svg>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Data tidak ditemukan</h2>
            <p class="text-gray-600 mb-6">Penawaran yang Anda cari tidak tersedia atau telah dihapus.</p>
            <a href="{{ route('penawaran.index') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                Kembali ke Daftar Penawaran
            </a>
        </div>
    @else
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 text-gray-600 mb-4">
                <a href="{{ route('penawaran.index') }}" class="hover:text-blue-600">Penawaran</a>
                <span>/</span>
                <span class="text-gray-900">{{ $penawaran->no_penawaran }}</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('penawaran.edit', $penawaran->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                </a>
                <a href="{{ route('penawaran.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                    Kembali
                </a>
            </div>
        </div>
        <div class="flex items-center justify-between mt-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $penawaran->no_penawaran }}</h1>
                <p class="text-gray-600 mt-2">Dibuat pada {{ $penawaran->created_at ? \Carbon\Carbon::parse($penawaran->created_at)->format('d M Y H:i') : '-' }}</p>
            </div>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $penawaran->getStatusColor() }}">
                {{ $penawaran->getStatusLabel() }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Client Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penawaran</h2>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">No. Penawaran</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $penawaran->no_penawaran }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $penawaran->tanggal ? \Carbon\Carbon::parse($penawaran->tanggal)->format('d M Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Client</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $penawaran->client ? $penawaran->client->nama : '-' }}</p>
                            <p class="text-sm text-gray-600">{{ $penawaran->client ? ($penawaran->client->kontak . ' - ' . $penawaran->client->email) : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $penawaran->getStatusColor() }}">
                                {{ $penawaran->getStatusLabel() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Item</h2>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Material</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Harga Asli</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Margin %</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Harga Jual</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($penawaran->items as $key => $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $key + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $item->material ? $item->material->nama : '-' }}</p>
                                            <p class="text-xs text-gray-600">{{ $item->material ? $item->material->satuan : '-' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Rp {{ number_format($item->harga_asli, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($item->persentase_margin, 2, ',', '.') }}%</td>
                                    <td class="px-4 py-3 text-sm font-medium text-blue-600">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">Rp {{ number_format($item->harga_jual * $item->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column - Summary & Actions -->
        <div class="lg:col-span-1">
            <!-- Summary Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Keuangan</h2>

                <div class="space-y-3 pb-4 border-b border-gray-200">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Item:</span>
                        <span class="font-semibold text-gray-900">{{ $penawaran->items->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Biaya:</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($penawaran->total_biaya, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Margin:</span>
                        <span class="font-semibold text-blue-600">Rp {{ number_format($penawaran->total_margin, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="flex justify-between pt-4 mb-6">
                    <span class="text-lg font-bold text-gray-900">Grand Total:</span>
                    <span class="text-lg font-bold text-blue-600">Rp {{ number_format($penawaran->total_biaya + $penawaran->total_margin, 0, ',', '.') }}</span>
                </div>

                <div class="space-y-3">
                    @if($penawaran && $penawaran->id)
                        <button onclick="changeStatus()" class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">
                            Ubah Status
                        </button>
                        <a href="{{ route('inventory.index') }}?penawaran_id={{ $penawaran->id }}" class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-center">
                            Lihat Riwayat Inventory
                        </a>
                        <button onclick="showConfirm('Apakah Anda yakin ingin menghapus penawaran ini?', 'Hapus Penawaran', () => deletePenawaran())" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                            Hapus Penawaran
                        </button>
                    @else
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-yellow-800 text-sm">Data penawaran tidak lengkap</p>
                        </div>
                    @endif
                </div>
                </div>
            </div>

            <!-- Client Information Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Client</h2>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nama</p>
                        <p class="font-medium text-gray-900">{{ $penawaran->client ? $penawaran->client->nama : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Kontak</p>
                        <p class="font-medium text-gray-900">{{ $penawaran->client ? $penawaran->client->kontak : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Email</p>
                        <p class="font-medium text-gray-900">{{ $penawaran->client ? $penawaran->client->email : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Telepon</p>
                        <p class="font-medium text-gray-900">{{ $penawaran->client ? $penawaran->client->telepon : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Alamat</p>
                        <p class="font-medium text-gray-900">{{ $penawaran->client ? $penawaran->client->alamat : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Modal -->
    <div id="statusModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl max-w-sm w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Ubah Status Penawaran</h3>
            <form id="statusForm" action="{{ route('penawaran.updateStatus', $penawaran->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                    <select id="newStatus" name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Status --</option>
                        <option value="draft" {{ $penawaran->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="disetujui" {{ $penawaran->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ $penawaran->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="dibatalkan" {{ $penawaran->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeStatusModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))
        <script>
            showToast("{{ session('success') }}", 'success');
        </script>
    @endif

    <script>
        // Store penawaran ID untuk digunakan di JavaScript
        const penawaranId = @json($penawaran ? $penawaran->id : null);
        const penawaranClientId = @json($penawaran ? $penawaran->client_id : null);
        const penawaranTanggal = @json($penawaran ? ($penawaran->tanggal ? \Carbon\Carbon::parse($penawaran->tanggal)->format('Y-m-d') : '') : '');

        // Validasi data
        if (!penawaranId) {
            console.error('Error: Penawaran ID tidak ditemukan');
        }

        function changeStatus() {
            if (!penawaranId) {
                showToast('Data penawaran tidak valid', 'error');
                return;
            }
            document.getElementById('statusModal').style.display = 'flex';
        }

        function closeStatusModal() {
            document.getElementById('statusModal').style.display = 'none';
        }

        function deletePenawaran() {
            if (!penawaranId) {
                showToast('Data penawaran tidak valid', 'error');
                return;
            }
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/quotations/' + penawaranId;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    </script>
    @endif
@endsection
