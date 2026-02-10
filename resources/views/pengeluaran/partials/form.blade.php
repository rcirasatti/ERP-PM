<!-- Proyek -->
<div>
    <label for="proyek_id" class="block text-sm font-medium text-gray-700 mb-2">Proyek *</label>
    <select name="proyek_id" id="proyek_id" required
        class="searchable-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('proyek_id') border-red-500 @enderror"
        {{ request('proyek_id') && !isset($pengeluaran) ? 'disabled' : '' }}>
        <option value="">-- Pilih Proyek --</option>
        @foreach ($projects as $project)
            <option value="{{ $project->id }}" 
                {{ old('proyek_id', request('proyek_id') ?? $pengeluaran->proyek_id ?? '') == $project->id ? 'selected' : '' }}>
                {{ $project->nama }}
            </option>
        @endforeach
    </select>
    @if(request('proyek_id') && !isset($pengeluaran))
        <input type="hidden" name="proyek_id" value="{{ request('proyek_id') }}">
    @endif
    @error('proyek_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Tanggal -->
<div>
    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal *</label>
    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', isset($pengeluaran) ? $pengeluaran->tanggal->format('Y-m-d') : date('Y-m-d')) }}" required
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('tanggal') border-red-500 @enderror">
    @error('tanggal')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Kategori -->
<div>
    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
    <select name="kategori" id="kategori" required
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('kategori') border-red-500 @enderror">
        <option value="">-- Pilih Kategori --</option>
        <option value="material" {{ old('kategori', $pengeluaran->kategori ?? '') == 'material' ? 'selected' : '' }}>Material</option>
        <option value="gaji" {{ old('kategori', $pengeluaran->kategori ?? '') == 'gaji' ? 'selected' : '' }}>Gaji</option>
        <option value="bahan_bakar" {{ old('kategori', $pengeluaran->kategori ?? '') == 'bahan_bakar' ? 'selected' : '' }}>Bahan Bakar</option>
        <option value="peralatan" {{ old('kategori', $pengeluaran->kategori ?? '') == 'peralatan' ? 'selected' : '' }}>Peralatan</option>
        <option value="lainnya" {{ old('kategori', $pengeluaran->kategori ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
    </select>
    @error('kategori')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Deskripsi -->
<div>
    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
    <textarea name="deskripsi" id="deskripsi" rows="4" required
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('deskripsi') border-red-500 @enderror"
        placeholder="Masukkan deskripsi pengeluaran secara detail">{{ old('deskripsi', $pengeluaran->deskripsi ?? '') }}</textarea>
    @error('deskripsi')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Jumlah -->
<div>
    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp) *</label>
    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', $pengeluaran->jumlah ?? '') }}" step="0.01" min="0" required
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('jumlah') border-red-500 @enderror"
        placeholder="Contoh: 500000">
    @error('jumlah')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Bukti File -->
<div>
    <label for="bukti_file" class="block text-sm font-medium text-gray-700 mb-2">Bukti (Upload File) *</label>
    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition" id="dropZone">
        <input type="file" name="bukti_file" id="bukti_file" class="hidden" required
            accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx">
        
        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        
        <p class="text-gray-700 font-medium">Drag & drop file atau <span class="text-blue-600 hover:text-blue-700">klik untuk upload</span></p>
        <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG, GIF, DOC, DOCX, XLS, XLSX (Max 5 MB)</p>
        
        <div id="fileInfo" class="mt-3 text-sm text-gray-600"></div>
    </div>
    
    @if(isset($pengeluaran) && $pengeluaran->bukti_file)
        <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800 font-medium">File saat ini:</p>
            <a href="{{ route('pengeluaran.preview-bukti', $pengeluaran->id) }}" target="_blank" class="text-blue-600 hover:text-blue-700 underline text-sm">
                {{ basename($pengeluaran->bukti_file) }}
            </a>
        </div>
    @endif
    
    @error('bukti_file')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
    // File upload dengan drag & drop
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('bukti_file');
    const fileInfo = document.getElementById('fileInfo');

    // Click to upload
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag & drop events
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        fileInput.files = e.dataTransfer.files;
        updateFileInfo();
    });

    // File input change
    fileInput.addEventListener('change', updateFileInfo);

    function updateFileInfo() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            fileInfo.innerHTML = `<span class="text-green-600">✓ ${file.name} (${sizeMB} MB)</span>`;
        } else {
            fileInfo.innerHTML = '';
        }
    }
</script>
