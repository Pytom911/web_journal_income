@php $isEditing = $income->exists; @endphp

<form method="POST" action="{{ $isEditing ? route('incomes.update', $income) : route('incomes.store') }}" class="space-y-5">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    <div>
        <label for="title" class="block text-sm font-medium text-ink">Judul income <span class="text-red-600">*</span></label>
        <input id="title" name="title" type="text" required maxlength="255" value="{{ old('title', $income->title) }}" aria-describedby="title-error" class="mt-2 min-h-11 w-full rounded-xl border border-line bg-surface px-4 text-sm text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200" placeholder="Contoh: Gaji bulan Juni">
        @error('title')
            <p id="title-error" class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="category_id" class="block text-sm font-medium text-ink">Kategori <span class="text-red-600">*</span></label>
            <select id="category_id" name="category_id" required aria-describedby="category-error" class="mt-2 min-h-11 w-full rounded-xl border border-line bg-surface px-4 text-sm text-ink focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $income->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p id="category-error" class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
            @if ($categories->isEmpty())
                <p class="mt-1.5 text-xs text-muted">Belum ada kategori aktif. <a href="{{ route('categories.create') }}" class="font-medium text-brand-700 underline">Tambah kategori</a> dulu.</p>
            @endif
        </div>

        <div>
            <label for="received_at" class="block text-sm font-medium text-ink">Tanggal diterima <span class="text-red-600">*</span></label>
            <input id="received_at" name="received_at" type="date" required value="{{ old('received_at', $income->received_at?->toDateString() ?? now()->toDateString()) }}" aria-describedby="date-error" class="mt-2 min-h-11 w-full rounded-xl border border-line bg-surface px-4 text-sm text-ink focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
            @error('received_at')
                <p id="date-error" class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="amount" class="block text-sm font-medium text-ink">Jumlah (Rp) <span class="text-red-600">*</span></label>
        <input id="amount" name="amount" type="number" min="0" step="0.01" required value="{{ old('amount', $income->amount) }}" aria-describedby="amount-help amount-error" class="mt-2 min-h-11 w-full rounded-xl border border-line bg-surface px-4 text-sm text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200" placeholder="1500000">
        <p id="amount-help" class="mt-1.5 text-xs text-muted">Isi angka tanpa titik, contoh: 1500000</p>
        @error('amount')
            <p id="amount-error" class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="source" class="block text-sm font-medium text-ink">Sumber <span class="font-normal text-muted">(opsional)</span></label>
        <input id="source" name="source" type="text" maxlength="255" value="{{ old('source', $income->source) }}" class="mt-2 min-h-11 w-full rounded-xl border border-line bg-surface px-4 text-sm text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200" placeholder="Contoh: PT Contoh / Proyek website">
    </div>

    <div>
        <label for="note" class="block text-sm font-medium text-ink">Catatan <span class="font-normal text-muted">(opsional)</span></label>
        <textarea id="note" name="note" rows="3" maxlength="2000" class="mt-2 w-full rounded-xl border border-line bg-surface px-4 py-3 text-sm text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200" placeholder="Detail tambahan jika perlu">{{ old('note', $income->note) }}</textarea>
    </div>

    <div class="flex flex-wrap gap-3 border-t border-line pt-5">
        <button type="submit" class="min-h-11 rounded-xl bg-brand-700 px-6 text-sm font-semibold text-white transition-colors duration-200 hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
            {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Income' }}
        </button>

        <a href="{{ route('incomes.index') }}" class="inline-flex min-h-11 items-center rounded-xl border border-line px-6 text-sm font-medium text-muted transition-colors duration-200 hover:bg-brand-50 hover:text-brand-800">
            Batal
        </a>
    </div>
</form>
