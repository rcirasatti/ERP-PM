<!-- Copy from Previous Modal -->
<div id="copyModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-40">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Copy Item dari Penawaran Sebelumnya</h3>
            <button onclick="closeCopyModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="copyForm" class="space-y-4">
            @csrf
            
            <!-- Source Penawaran Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Penawaran Sumber</label>
                <select id="sourcePenawaran" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required onchange="loadPriceTrend()">
                    <option value="">-- Pilih Penawaran --</option>
                    <!-- Diisi oleh JavaScript -->
                </select>
                <p class="text-xs text-gray-500 mt-1">Penawaran dari client yang sama</p>
            </div>

            <!-- Price Strategy Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Strategi Harga</label>
                <div class="space-y-2">
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 transition">
                        <input type="radio" name="price_strategy" value="keep" checked class="w-4 h-4 text-blue-600">
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">Pertahankan Harga</p>
                            <p class="text-sm text-gray-500">Gunakan harga dari penawaran sumber</p>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 transition">
                        <input type="radio" name="price_strategy" value="latest" class="w-4 h-4 text-blue-600">
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">Harga Material Terbaru</p>
                            <p class="text-sm text-gray-500">Gunakan harga terkini dari master data</p>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 transition">
                        <input type="radio" name="price_strategy" value="average" class="w-4 h-4 text-blue-600">
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">Rata-rata Harga Historis</p>
                            <p class="text-sm text-gray-500">Gunakan rata-rata dari penawaran sebelumnya</p>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 transition">
                        <input type="radio" name="price_strategy" value="override" class="w-4 h-4 text-blue-600">
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">Harga Manual</p>
                            <p class="text-sm text-gray-500">Atur harga setiap item secara manual</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm font-medium text-gray-700 mb-3">Preview Item yang akan dicopy:</p>
                <div id="previewItems" class="space-y-2">
                    <p class="text-sm text-gray-500">Pilih penawaran sumber terlebih dahulu</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeCopyModal()" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition font-medium">
                    Batal
                </button>
                <button type="submit" id="copySubmitBtn" onclick="submitCopy()" class="flex-1 px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition font-medium" disabled>
                    Copy Items
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden input to pass penawaran ID -->
<input type="hidden" id="targetPenawaranId" value="{{ $penawaran->id }}">
<input type="hidden" id="clientId" value="{{ $penawaran->client_id }}">
