<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0891b2">

    <title>Masuk — {{ config('app.name', 'Journal') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-canvas font-sans text-ink antialiased">
    <div class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="grid w-full max-w-4xl overflow-hidden rounded-3xl border border-line bg-surface shadow-sm md:grid-cols-2">
            <div class="hidden flex-col justify-between bg-brand-700 p-10 text-white md:flex">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </span>
                    <span class="text-sm font-semibold">Journal Income</span>
                </div>

                <div>
                    <h1 class="text-3xl font-semibold leading-tight tracking-tight">Semua pemasukan, tercatat rapi.</h1>
                    <p class="mt-4 text-sm text-brand-100">Lihat total income, grafik bulanan, dan kelola kategori dari satu tempat.</p>
                </div>

                <p class="text-xs text-brand-100">Catatan pribadi · satu akun · sederhana</p>
            </div>

            <div class="p-8 sm:p-10">
                <h2 class="text-2xl font-semibold tracking-tight text-ink">Selamat datang kembali</h2>
                <p class="mt-1 text-sm text-muted">Masuk untuk membuka jurnal income kamu.</p>

                <x-alert-box class="mb-5" />

                <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-ink">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="username"
                            required
                            value="{{ old('email') }}"
                            aria-describedby="email-error"
                            class="mt-2 min-h-11 w-full rounded-xl border border-line bg-surface px-4 text-sm text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200"
                            placeholder="nama@contoh.com"
                        >
                        @error('email')
                            <p id="email-error" class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-ink">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            aria-describedby="password-error"
                            class="mt-2 min-h-11 w-full rounded-xl border border-line bg-surface px-4 text-sm text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200"
                            placeholder="••••••••"
                        >
                        @error('password')
                            <p id="password-error" class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-2 text-sm text-muted">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-line text-brand-600 focus:ring-brand-300">
                        Ingat saya di perangkat ini
                    </label>

                    <button type="submit" class="min-h-11 w-full rounded-xl bg-brand-700 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                        Masuk
                    </button>
                </form>

                @if (app()->environment('local'))
                    <p class="mt-6 rounded-xl bg-brand-50 px-4 py-3 text-xs text-brand-800">
                        Akun awal: <span class="font-semibold">{{ config('journal.owner.email') }}</span> / <span class="font-semibold">password</span>. Ganti lewat variabel <code>JOURNAL_OWNER_*</code> di <code>.env</code>.
                    </p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
