@props(['label', 'hint' => null])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-line bg-surface p-5 shadow-sm']) }}>
    <p class="text-sm font-medium text-muted">{{ $label }}</p>

    <div class="mt-3 text-2xl font-semibold tracking-tight text-ink">
        {{ $slot }}
    </div>

    @if ($hint)
        <p class="mt-1 text-xs text-muted">{{ $hint }}</p>
    @endif
</div>
