@php $isEditing = $category->exists; @endphp

<form method="POST" action="{{ $isEditing ? route('categories.update', $category) : route('categories.store') }}" class="space-y-5">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="block text-sm font-medium text-ink">Nama kategori <span class="text-red-600">*</span></label>
        <input id="name" name="name" type="text" required maxlength="255" value="{{ old('name', $category->name) }}" aria-describedby="name-error" class="mt-2 min-h-11 w-full rounded-xl border border-line bg-surface px-4 text-sm text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200" placeholder="Contoh: Honorarium">
        @error('name')
            <p id="name-error" class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="color" class="block text-sm font-medium text-ink">Warna</label>
            <div class="mt-2 flex items-center gap-3">
                <input id="color" name="color" type="color" value="{{ old('color', $category->color ?: '#06b6d4') }}" aria-describedby="color-error" class="h-11 w-16 cursor-pointer rounded-xl border border-line bg-surface p-1 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                <span class="text-xs text-muted">Dipakai untuk penanda kategori di grafik dan tabel.</span>
            </div>
            @error('color')
                <p id="color-error" class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-xl border border-line p-4">
            <label class="flex items-start gap-3 text-sm text-ink">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $isEditing ? $category->is_active : true)) class="mt-0.5 h-4 w-4 rounded border-line text-brand-600 focus:ring-brand-300">
                <span>
                    <span class="block font-medium">Kategori aktif</span>
                    <span class="mt-0.5 block text-xs text-muted">Kategori aktif bisa dipilih saat menambah income.</span>
                </span>
            </label>
        </div>
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-ink">Deskripsi <span class="font-normal text-muted">(opsional)</span></label>
        <textarea id="description" name="description" rows="3" maxlength="1000" class="mt-2 w-full rounded-xl border border-line bg-surface px-4 py-3 text-sm text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200" placeholder="Contoh: Pemasukan dari pekerjaan tetap">{{ old('description', $category->description) }}</textarea>
    </div>

    <div class="flex flex-wrap gap-3 border-t border-line pt-5">
        <button type="submit" class="min-h-11 rounded-xl bg-brand-700 px-6 text-sm font-semibold text-white transition-colors duration-200 hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
            {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Kategori' }}
        </button>

        <a href="{{ route('categories.index') }}" class="inline-flex min-h-11 items-center rounded-xl border border-line px-6 text-sm font-medium text-muted transition-colors duration-200 hover:bg-brand-50 hover:text-brand-800">
            Batal
        </a>
    </div>
</form>
