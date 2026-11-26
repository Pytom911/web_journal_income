@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('categories.index') }}" class="text-sm font-medium text-brand-700 hover:text-brand-900">← Kembali ke kategori</a>

        <div class="mt-4 rounded-2xl border border-line bg-surface p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-ink">Edit kategori</h1>
            <p class="mt-1 text-sm text-muted">Perbarui nama, warna, atau status kategori ini.</p>

            <div class="mt-7">
                @include('categories.form', ['category' => $category])
            </div>
        </div>
    </div>
@endsection
