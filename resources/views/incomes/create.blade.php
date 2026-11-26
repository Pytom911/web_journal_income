@extends('layouts.app')

@section('title', 'Tambah Income')

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('incomes.index') }}" class="text-sm font-medium text-brand-700 hover:text-brand-900">← Kembali ke income</a>

        <div class="mt-4 rounded-2xl border border-line bg-surface p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-ink">Tambah income baru</h1>
            <p class="mt-1 text-sm text-muted">Catat pemasukan dari sumber apa pun, lalu pilih kategorinya.</p>

            <div class="mt-7">
                @include('incomes.form', ['income' => $income, 'categories' => $categories])
            </div>
        </div>
    </div>
@endsection
