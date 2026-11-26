<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0891b2">

    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Journal') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-canvas font-sans text-ink antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="hidden w-64 shrink-0 flex-col border-r border-line bg-surface px-5 py-7 lg:flex">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-lg focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-white shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </span>
                <span>
                    <span class="block text-sm font-semibold text-ink">Journal Income</span>
                    <span class="block text-xs text-muted">Catatan pemasukanmu</span>
                </span>
            </a>

            <nav class="mt-10 flex flex-1 flex-col gap-1" aria-label="Navigasi utama">
                <a href="{{ route('dashboard') }}" @class([
                    'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-200',
                    'bg-brand-50 text-brand-800' => request()->routeIs('dashboard'),
                    'text-muted hover:bg-brand-50 hover:text-brand-800' => ! request()->routeIs('dashboard'),
                ]) aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('incomes.index') }}" @class([
                    'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-200',
                    'bg-brand-50 text-brand-800' => request()->routeIs('incomes.*'),
                    'text-muted hover:bg-brand-50 hover:text-brand-800' => ! request()->routeIs('incomes.*'),
                ]) aria-current="{{ request()->routeIs('incomes.*') ? 'page' : 'false' }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 11.219 12.768 11 12 11c-.768 0-1.536.219-2.121.659-1.172.879-1.172 2.303 0 3.182ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Income
                </a>

                <a href="{{ route('categories.index') }}" @class([
                    'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-200',
                    'bg-brand-50 text-brand-800' => request()->routeIs('categories.*'),
                    'text-muted hover:bg-brand-50 hover:text-brand-800' => ! request()->routeIs('categories.*'),
                ]) aria-current="{{ request()->routeIs('categories.*') ? 'page' : 'false' }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>
                    Kategori
                </a>
            </nav>

            <div class="border-t border-line pt-5">
                <p class="truncate text-sm font-medium text-ink">{{ auth()->user()->name }}</p>
                <p class="mb-3 truncate text-xs text-muted">{{ auth()->user()->email }}</p>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-muted transition-colors duration-200 hover:bg-red-50 hover:text-red-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="flex items-center justify-between border-b border-line bg-surface px-4 py-4 lg:hidden">
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-brand-800">Journal Income</a>
                <span class="text-xs text-muted">{{ auth()->user()->name }}</span>
            </header>

            <main class="flex-1 px-4 pb-28 pt-6 sm:px-6 lg:px-10 lg:pb-10">
                <x-alert-box />

                @yield('content')
            </main>

            <nav class="fixed inset-x-0 bottom-0 z-20 flex items-center justify-around border-t border-line bg-surface px-2 pb-[env(safe-area-inset-bottom)] pt-2 lg:hidden" aria-label="Navigasi bawah">
                <a href="{{ route('dashboard') }}" @class([
                    'flex min-h-11 min-w-16 flex-col items-center gap-1 rounded-xl px-3 py-1.5 text-xs font-medium',
                    'bg-brand-50 text-brand-800' => request()->routeIs('dashboard'),
                    'text-muted' => ! request()->routeIs('dashboard'),
                ])>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('incomes.index') }}" @class([
                    'flex min-h-11 min-w-16 flex-col items-center gap-1 rounded-xl px-3 py-1.5 text-xs font-medium',
                    'bg-brand-50 text-brand-800' => request()->routeIs('incomes.*'),
                    'text-muted' => ! request()->routeIs('incomes.*'),
                ])>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 11.219 12.768 11 12 11c-.768 0-1.536.219-2.121.659-1.172.879-1.172 2.303 0 3.182ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Income
                </a>

                <a href="{{ route('categories.index') }}" @class([
                    'flex min-h-11 min-w-16 flex-col items-center gap-1 rounded-xl px-3 py-1.5 text-xs font-medium',
                    'bg-brand-50 text-brand-800' => request()->routeIs('categories.*'),
                    'text-muted' => ! request()->routeIs('categories.*'),
                ])>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>
                    Kategori
                </a>
            </nav>
        </div>
    </div>
</body>
</html>
