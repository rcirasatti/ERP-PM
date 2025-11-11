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
