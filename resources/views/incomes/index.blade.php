@extends('layouts.app')

@section('title', 'Daftar Income')

@section('content')
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-brand-700">Catatan pemasukan</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-ink sm:text-3xl">Daftar income</h1>
                <p class="mt-1 text-sm text-muted">Total pada filter ini: <span class="font-semibold text-brand-800"><x-rupiah :amount="$totalFiltered" /></span></p>
            </div>

            <a href="{{ route('incomes.create') }}" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-brand-700 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                Tambah Income
            </a>
        </div>

        <form method="GET" action="{{ route('incomes.index') }}" class="mt-7 flex flex-wrap items-end gap-3 rounded-2xl border border-line bg-surface p-4 shadow-sm">
            <div class="min-w-48 flex-1">
                <label for="bulan" class="block text-xs font-semibold uppercase tracking-wide text-muted">Filter bulan</label>
                <input id="bulan" name="bulan" type="month" value="{{ request('bulan', $selectedMonth) }}" class="mt-2 min-h-11 w-full rounded-xl border border-line bg-surface px-3 text-sm text-ink focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
            </div>

            <button type="submit" class="min-h-11 rounded-xl bg-brand-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-brand-700">Terapkan</button>
            <a href="{{ route('incomes.index') }}" class="inline-flex min-h-11 items-center rounded-xl border border-line px-5 text-sm font-medium text-muted hover:bg-brand-50 hover:text-brand-800">Reset</a>
        </form>

        <section class="mt-6 overflow-hidden rounded-2xl border border-line bg-surface shadow-sm">
            @if ($incomes->isEmpty())
                <x-empty-state title="Belum ada income" description="Mulai catat pemasukan pertama kamu.">
                    <a href="{{ route('incomes.create') }}" class="inline-flex min-h-11 items-center rounded-xl bg-brand-700 px-5 text-sm font-semibold text-white hover:bg-brand-800">Tambah Income</a>
                </x-empty-state>
            @else
                <div class="hidden overflow-x-auto md:block">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-brand-50/60">
                            <tr class="text-xs uppercase tracking-wide text-muted">
                                <th scope="col" class="px-5 py-3">Tanggal</th>
                                <th scope="col" class="px-5 py-3">Judul</th>
                                <th scope="col" class="px-5 py-3">Kategori</th>
                                <th scope="col" class="px-5 py-3">Sumber</th>
                                <th scope="col" class="px-5 py-3 text-right">Jumlah</th>
                                <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($incomes as $income)
                                <tr class="hover:bg-brand-50/40">
                                    <td class="whitespace-nowrap px-5 py-4 text-muted">{{ $income->received_at->translatedFormat('d M Y') }}</td>
                                    <td class="px-5 py-4 font-medium text-ink">{{ $income->title }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-2 rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-800">
                                            <span class="h-2 w-2 rounded-full" style="background-color: {{ $income->category?->color ?? '#94a3b8' }}"></span>
                                            {{ $income->category?->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-muted">{{ $income->source ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-brand-800"><x-rupiah :amount="$income->amount" /></td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('incomes.edit', $income) }}" class="rounded-lg px-3 py-2 text-xs font-semibold text-brand-700 hover:bg-brand-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">Edit</a>

                                            <details class="relative">
                                                <summary class="cursor-pointer rounded-lg px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500">Hapus</summary>
                                                <div class="absolute right-0 z-10 mt-2 w-56 rounded-xl border border-line bg-surface p-3 text-left shadow-lg">
                                                    <p class="text-xs text-muted">Yakin hapus income ini?</p>
                                                    <form method="POST" action="{{ route('incomes.destroy', $income) }}" class="mt-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="min-h-9 w-full rounded-lg bg-red-600 px-3 text-xs font-semibold text-white hover:bg-red-700">Ya, hapus</button>
                                                    </form>
                                                </div>
                                            </details>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <ul class="divide-y divide-line md:hidden">
                    @foreach ($incomes as $income)
                        <li class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-ink">{{ $income->title }}</p>
                                    <p class="mt-0.5 text-xs text-muted">{{ $income->received_at->translatedFormat('d M Y') }} · {{ $income->category?->name ?? '—' }}</p>
                                </div>
                                <x-rupiah :amount="$income->amount" class="shrink-0 text-sm font-semibold text-brand-800" />
                            </div>

                            <div class="mt-3 flex items-center gap-3">
                                <a href="{{ route('incomes.edit', $income) }}" class="min-h-9 rounded-lg bg-brand-50 px-4 py-2 text-xs font-semibold text-brand-800">Edit</a>

                                <details>
                                    <summary class="cursor-pointer rounded-lg px-3 py-2 text-xs font-semibold text-red-600">Hapus</summary>
                                    <form method="POST" action="{{ route('incomes.destroy', $income) }}" class="mt-2 flex items-center gap-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="min-h-9 rounded-lg bg-red-600 px-3 text-xs font-semibold text-white">Ya, hapus</button>
                                    </form>
                                </details>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="border-t border-line px-5 py-4">{{ $incomes->links() }}</div>
            @endif
        </section>
    </div>
@endsection
