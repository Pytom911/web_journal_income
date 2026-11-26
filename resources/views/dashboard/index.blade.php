@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-brand-700">Ringkasan</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-ink sm:text-3xl">Dashboard income</h1>
                <p class="mt-1 text-sm text-muted">Pantau total pemasukan dan trennya dari waktu ke waktu.</p>
            </div>

            <a href="{{ route('incomes.create') }}" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-brand-700 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                Tambah Income
            </a>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="mt-7 rounded-2xl border border-line bg-surface p-4 shadow-sm sm:p-5" aria-label="Filter income">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label for="bulan" class="block text-xs font-semibold uppercase tracking-wide text-muted">Bulan</label>
                    <input id="bulan" name="bulan" type="month" value="{{ request('bulan', $selectedMonth) }}" class="mt-2 min-h-11 w-full rounded-xl border border-line bg-surface px-3 text-sm text-ink focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                </div>

                <div>
                    <label for="rentang" class="block text-xs font-semibold uppercase tracking-wide text-muted">Rentang</label>
                    <select id="rentang" name="rentang" class="mt-2 min-h-11 w-full rounded-xl border border-line bg-surface px-3 text-sm text-ink focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                        <option value="1" @selected($selectedRange === '1')>1 bulan</option>
                        <option value="3" @selected($selectedRange === '3')>3 bulan</option>
                        <option value="6" @selected($selectedRange === '6')>6 bulan</option>
                        <option value="12" @selected($selectedRange === '12')>12 bulan</option>
                        <option value="semua" @selected($selectedRange === 'semua')>Semua</option>
                    </select>
                </div>

                <div>
                    <label for="tampilan" class="block text-xs font-semibold uppercase tracking-wide text-muted">Tampilan</label>
                    <select id="tampilan" name="tampilan" class="mt-2 min-h-11 w-full rounded-xl border border-line bg-surface px-3 text-sm text-ink focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                        <option value="semua" @selected($selectedView === 'semua')>Semua transaksi</option>
                        <option value="total" @selected($selectedView === 'total')>Total per kategori</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="min-h-11 flex-1 rounded-xl bg-brand-600 px-4 text-sm font-semibold text-white transition-colors duration-200 hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                        Terapkan
                    </button>

                    <a href="{{ route('dashboard') }}" class="inline-flex min-h-11 items-center rounded-xl border border-line px-4 text-sm font-medium text-muted transition-colors duration-200 hover:bg-brand-50 hover:text-brand-800">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-kpi-card label="Total income (periode)" :hint="'Dari '.$selectedRange.' bulan terpilih'">
                <x-rupiah :amount="$totalFiltered" />
            </x-kpi-card>

            <x-kpi-card label="Income bulan ini" hint="Bulan berjalan">
                <x-rupiah :amount="$totalBulanIni" />
            </x-kpi-card>

            <x-kpi-card label="Jumlah transaksi" hint="Sesuai filter">
                {{ $countFiltered }}
            </x-kpi-card>

            <x-kpi-card label="Rata-rata per transaksi" hint="Sesuai filter">
                <x-rupiah :amount="$average" />
            </x-kpi-card>
        </div>

        <p class="mt-3 text-xs text-muted">
            Total keseluruhan semua income: <span class="font-semibold text-brand-800"><x-rupiah :amount="$totalKeseluruhan" /></span>
        </p>

        <div class="mt-6">
            <x-income-chart :points="$chart" :mode="$chartType" :title="'Grafik income'" />
        </div>

        <section class="mt-6 rounded-2xl border border-line bg-surface p-5 shadow-sm" aria-labelledby="detail-income">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                <h2 id="detail-income" class="text-base font-semibold text-ink">
                    {{ $selectedView === 'total' ? 'Total income per kategori' : 'Transaksi pada periode ini' }}
                </h2>

                <a href="{{ route('incomes.index') }}" class="text-sm font-medium text-brand-700 hover:text-brand-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                    Kelola semua income →
                </a>
            </div>

            @if ($selectedView === 'total')
                @if ($totalsByCategory->isEmpty())
                    <x-empty-state title="Belum ada income pada periode ini" description="Coba ubah bulan atau rentang filter." />
                @else
                    @php $maxTotal = (float) ($totalsByCategory->max('total') ?: 1); @endphp

                    <ul class="space-y-3">
                        @foreach ($totalsByCategory as $item)
                            <li class="rounded-xl border border-line p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="flex items-center gap-2 text-sm font-medium text-ink">
                                        <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $item['category']?->color ?? '#94a3b8' }}"></span>
                                        {{ $item['category']?->name ?? 'Kategori dihapus' }}
                                    </span>
                                    <x-rupiah :amount="$item['total']" class="text-sm font-semibold text-brand-800" />
                                </div>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-brand-50" role="presentation">
                                    <div class="h-full rounded-full bg-brand-500" style="width: {{ round(($item['total'] / $maxTotal) * 100, 1) }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @elseif ($transactions->isEmpty())
                <x-empty-state title="Belum ada transaksi pada periode ini" description="Tambahkan income pertama atau ubah filter bulan.">
                    <a href="{{ route('incomes.create') }}" class="inline-flex min-h-11 items-center rounded-xl bg-brand-700 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-brand-800">
                        Tambah Income
                    </a>
                </x-empty-state>
            @else
                <div class="hidden overflow-x-auto sm:block">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-line text-xs uppercase tracking-wide text-muted">
                                <th scope="col" class="py-3 pr-4">Tanggal</th>
                                <th scope="col" class="py-3 pr-4">Judul</th>
                                <th scope="col" class="py-3 pr-4">Kategori</th>
                                <th scope="col" class="py-3 pr-4">Sumber</th>
                                <th scope="col" class="py-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($transactions as $income)
                                <tr>
                                    <td class="py-3 pr-4 text-muted">{{ $income->received_at->translatedFormat('d M Y') }}</td>
                                    <td class="py-3 pr-4 font-medium text-ink">{{ $income->title }}</td>
                                    <td class="py-3 pr-4">
                                        <span class="inline-flex items-center gap-2 text-muted">
                                            <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $income->category?->color ?? '#94a3b8' }}"></span>
                                            {{ $income->category?->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-4 text-muted">{{ $income->source ?? '—' }}</td>
                                    <td class="py-3 text-right font-semibold text-brand-800"><x-rupiah :amount="$income->amount" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <ul class="space-y-3 sm:hidden">
                    @foreach ($transactions as $income)
                        <li class="rounded-xl border border-line p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-ink">{{ $income->title }}</p>
                                    <p class="mt-0.5 text-xs text-muted">{{ $income->received_at->translatedFormat('d M Y') }} · {{ $income->category?->name ?? '—' }}</p>
                                </div>
                                <x-rupiah :amount="$income->amount" class="shrink-0 text-sm font-semibold text-brand-800" />
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-5">{{ $transactions->links() }}</div>
            @endif
        </section>
    </div>
@endsection
