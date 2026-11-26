@props(['points' => [], 'title' => 'Grafik income', 'mode' => 'bar'])

@php
    $pointCount = count($points);
    $max = (float) (collect($points)->max('total') ?: 1);
    $width = 800;
    $height = 240;
    $padLeft = 56;
    $padRight = 16;
    $padTop = 16;
    $padBottom = 40;
    $plotWidth = $width - $padLeft - $padRight;
    $plotHeight = $height - $padTop - $padBottom;
    $labelStep = max(1, (int) ceil($pointCount / 12));

    $formatRupiah = fn (float $value): string => 'Rp '.number_format($value, 0, ',', '.');
    $shortNumber = function (float $value): string {
        if ($value >= 1000000) {
            return number_format($value / 1000000, 1, ',', '.').' jt';
        }

        if ($value >= 1000) {
            return number_format($value / 1000, 0, ',', '.').' rb';
        }

        return number_format($value, 0, ',', '.');
    };

    $slotWidth = $plotWidth / max(1, $pointCount);
    $barWidth = max(2, $slotWidth * 0.55);
    $lineStep = $pointCount > 1 ? $plotWidth / ($pointCount - 1) : 0;
    $linePoints = collect($points)->map(fn (array $point, int $index): array => [
        round($padLeft + $index * $lineStep, 2),
        round($padTop + $plotHeight - ($plotHeight * ((float) $point['total'] / $max)), 2),
    ]);
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-line bg-surface p-5 shadow-sm']) }}>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <div>
            <h2 class="text-base font-semibold text-ink">{{ $title }}</h2>
            <p class="mt-0.5 text-xs text-muted">{{ $mode === 'bar' ? 'Per hari pada bulan terpilih' : 'Per bulan pada rentang terpilih' }}</p>
        </div>

        <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-800">
            {{ $mode === 'bar' ? 'Harian' : 'Bulanan' }}
        </span>
    </div>

    <svg class="h-64 w-full" viewBox="0 0 {{ $width }} {{ $height }}" role="img" aria-labelledby="income-chart-title income-chart-desc">
        <title id="income-chart-title">{{ $title }}</title>
        <desc id="income-chart-desc">Grafik income dengan nilai tiap periode. Gunakan tabel di bawah sebagai alternatif teks.</desc>

        @foreach ([0, 0.5, 1] as $ratio)
            @php
                $gridY = $padTop + $plotHeight - ($plotHeight * $ratio);
            @endphp

            <line x1="{{ $padLeft }}" y1="{{ $gridY }}" x2="{{ $width - $padRight }}" y2="{{ $gridY }}" class="stroke-line" stroke-width="1" />
            <text x="{{ $padLeft - 8 }}" y="{{ $gridY + 4 }}" text-anchor="end" class="fill-muted text-[10px]">{{ $shortNumber($max * $ratio) }}</text>
        @endforeach

        @if ($mode === 'line')
            <polyline
                points="{{ $linePoints->map(fn (array $coordinate): string => $coordinate[0].','.$coordinate[1])->join(' ') }}"
                fill="none"
                class="stroke-brand-500"
                stroke-width="3"
                stroke-linecap="round"
                stroke-linejoin="round"
            />

            @foreach ($points as $index => $point)
                @php $coordinate = $linePoints[$index]; @endphp

                <circle
                    cx="{{ $coordinate[0] }}"
                    cy="{{ $coordinate[1] }}"
                    r="4"
                    class="cursor-pointer fill-surface stroke-brand-600 transition-colors hover:fill-brand-100 focus:fill-brand-200 focus:outline-none"
                    stroke-width="2"
                    tabindex="0"
                    aria-label="{{ $point['label'] }}: {{ $formatRupiah((float) $point['total']) }}"
                >
                    <title>{{ $point['label'] }}: {{ $formatRupiah((float) $point['total']) }}</title>
                </circle>

                @if ($index % $labelStep === 0)
                    <text x="{{ $coordinate[0] }}" y="{{ $height - 12 }}" text-anchor="middle" class="fill-muted text-[10px]">{{ $point['label'] }}</text>
                @endif
            @endforeach
        @else
            @foreach ($points as $index => $point)
                @php
                    $barHeight = $plotHeight * ((float) $point['total'] / $max);
                    $x = $padLeft + $index * $slotWidth + ($slotWidth - $barWidth) / 2;
                    $y = $padTop + $plotHeight - $barHeight;
                @endphp

                <rect
                    x="{{ round($x, 2) }}"
                    y="{{ round($y, 2) }}"
                    width="{{ round($barWidth, 2) }}"
                    height="{{ round($barHeight, 2) }}"
                    rx="3"
                    class="cursor-pointer fill-brand-500 transition-colors hover:fill-brand-700 focus:fill-brand-700 focus:outline-none"
                    tabindex="0"
                    aria-label="{{ $point['label'] }}: {{ $formatRupiah((float) $point['total']) }}"
                >
                    <title>{{ $point['label'] }}: {{ $formatRupiah((float) $point['total']) }}</title>
                </rect>

                @if ($index % $labelStep === 0)
                    <text x="{{ round($x + $barWidth / 2, 2) }}" y="{{ $height - 12 }}" text-anchor="middle" class="fill-muted text-[10px]">{{ $point['label'] }}</text>
                @endif
            @endforeach
        @endif
    </svg>

    <details class="mt-4 group border-t border-line pt-3">
        <summary class="cursor-pointer text-sm font-medium text-brand-700 hover:text-brand-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
            Lihat data grafik
        </summary>

        <div class="mt-3 max-h-64 overflow-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase tracking-wide text-muted">
                    <tr>
                        <th scope="col" class="py-2 pr-4">Periode</th>
                        <th scope="col" class="py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($points as $point)
                        <tr>
                            <th scope="row" class="py-2 pr-4 font-medium text-ink">{{ $point['label'] }}</th>
                            <td class="py-2 text-right text-muted"><x-rupiah :amount="$point['total']" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </details>
</div>
