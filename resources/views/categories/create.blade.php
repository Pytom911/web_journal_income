@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('categories.index') }}" class="text-sm font-medium text-brand-700 hover:text-brand-900">← Kembali ke kategori</a>

        <div class="mt-4 rounded-2xl border border-line bg-surface p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-ink">Tambah kategori baru</h1>
            <p class="mt-1 text-sm text-muted">Beri nama dan warna agar income lebih mudah dikenali.</p>

            <div class="mt-7">
                @include('categories.form', ['category' => $category])
            </div>
        </div>
    </div>
@endsection
