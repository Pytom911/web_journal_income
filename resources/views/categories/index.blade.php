@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-brand-700">Pengaturan income</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-ink sm:text-3xl">Kategori</h1>
                <p class="mt-1 text-sm text-muted">Kelola label pemasukan. Kategori yang sudah dipakai tidak bisa dihapus.</p>
            </div>

            <a href="{{ route('categories.create') }}" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-brand-700 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                Tambah Kategori
            </a>
        </div>

        <section class="mt-7 overflow-hidden rounded-2xl border border-line bg-surface shadow-sm">
            @if ($categories->isEmpty())
                <x-empty-state title="Belum ada kategori" description="Buat kategori pertama agar income bisa dikelompokkan.">
                    <a href="{{ route('categories.create') }}" class="inline-flex min-h-11 items-center rounded-xl bg-brand-700 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-brand-800">
                        Tambah Kategori
                    </a>
                </x-empty-state>
            @else
                <div class="hidden overflow-x-auto md:block">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-brand-50/60">
                            <tr class="text-xs uppercase tracking-wide text-muted">
                                <th scope="col" class="px-5 py-3">Kategori</th>
                                <th scope="col" class="px-5 py-3">Warna</th>
                                <th scope="col" class="px-5 py-3">Status</th>
                                <th scope="col" class="px-5 py-3">Jumlah income</th>
                                <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-brand-50/40">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="h-8 w-8 shrink-0 rounded-lg border border-line" style="background-color: {{ $category->color ?: '#94a3b8' }}"></span>
                                            <div>
                                                <p class="font-medium text-ink">{{ $category->name }}</p>
                                                <p class="mt-0.5 text-xs text-muted">{{ $category->description ?: 'Tanpa deskripsi' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 font-mono text-xs text-muted">{{ $category->color ?: '—' }}</td>
                                    <td class="px-5 py-4">
                                        @if ($category->is_active)
                                            <span class="inline-flex rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-800">Aktif</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-muted">{{ $category->incomes_count }} transaksi</td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('categories.edit', $category) }}" class="rounded-lg px-3 py-2 text-xs font-semibold text-brand-700 hover:bg-brand-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">Edit</a>

                                            @if ($category->incomes_count > 0)
                                                <form method="POST" action="{{ route('categories.toggle-status', $category) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="min-h-9 rounded-lg border border-line px-3 text-xs font-semibold text-muted transition-colors duration-200 hover:bg-brand-50 hover:text-brand-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                                                        {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </form>
                                            @else
                                                <details class="relative">
                                                    <summary class="cursor-pointer rounded-lg px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500">Hapus</summary>
                                                    <div class="absolute right-0 z-10 mt-2 w-56 rounded-xl border border-line bg-surface p-3 text-left shadow-lg">
                                                        <p class="text-xs text-muted">Yakin hapus kategori ini?</p>
                                                        <form method="POST" action="{{ route('categories.destroy', $category) }}" class="mt-2">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="min-h-9 w-full rounded-lg bg-red-600 px-3 text-xs font-semibold text-white hover:bg-red-700">Ya, hapus</button>
                                                        </form>
                                                    </div>
                                                </details>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <ul class="divide-y divide-line md:hidden">
                    @foreach ($categories as $category)
                        <li class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="h-8 w-8 shrink-0 rounded-lg border border-line" style="background-color: {{ $category->color ?: '#94a3b8' }}"></span>
                                    <div>
                                        <p class="text-sm font-semibold text-ink">{{ $category->name }}</p>
                                        <p class="mt-0.5 text-xs text-muted">{{ $category->description ?: 'Tanpa deskripsi' }}</p>
                                    </div>
                                </div>

                                @if ($category->is_active)
                                    <span class="inline-flex shrink-0 rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-800">Aktif</span>
                                @else
                                    <span class="inline-flex shrink-0 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Nonaktif</span>
                                @endif
                            </div>

                            <p class="mt-3 text-xs text-muted">{{ $category->incomes_count }} transaksi · Warna {{ $category->color ?: '—' }}</p>

                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                <a href="{{ route('categories.edit', $category) }}" class="min-h-9 rounded-lg bg-brand-50 px-4 py-2 text-xs font-semibold text-brand-800">Edit</a>

                                @if ($category->incomes_count > 0)
                                    <form method="POST" action="{{ route('categories.toggle-status', $category) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="min-h-9 rounded-lg border border-line px-4 text-xs font-semibold text-muted">
                                            {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @else
                                    <details>
                                        <summary class="cursor-pointer rounded-lg px-3 py-2 text-xs font-semibold text-red-600">Hapus</summary>
                                        <form method="POST" action="{{ route('categories.destroy', $category) }}" class="mt-2 flex items-center gap-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="min-h-9 rounded-lg bg-red-600 px-3 text-xs font-semibold text-white">Ya, hapus</button>
                                        </form>
                                    </details>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
