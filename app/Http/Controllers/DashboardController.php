<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    private const MONTH_LABELS = [
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
    ];

    /**
     * Display the income dashboard.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'bulan' => ['nullable', 'date_format:Y-m'],
            'rentang' => ['nullable', Rule::in(['1', '3', '6', '12', 'semua'])],
            'tampilan' => ['nullable', Rule::in(['semua', 'total'])],
        ]);

        $anchor = isset($validated['bulan'])
            ? Carbon::createFromFormat('Y-m', $validated['bulan'])->startOfMonth()
            : now()->startOfMonth();
        $rentang = $validated['rentang'] ?? '1';
        $tampilan = $validated['tampilan'] ?? 'semua';

        $start = $rentang === 'semua'
            ? Carbon::parse(Income::query()->min('received_at') ?? $anchor->toDateString())->startOfMonth()
            : $anchor->copy()->subMonths((int) $rentang - 1)->startOfMonth();
        $end = $anchor->copy()->endOfMonth();

        $filteredQuery = Income::query()->receivedBetween($start, $end);
        $totalFiltered = (float) $filteredQuery->sum('amount');
        $countFiltered = (int) (clone $filteredQuery)->count();
        $average = $countFiltered > 0 ? $totalFiltered / $countFiltered : 0.0;

        $totalBulanIni = (float) Income::query()
            ->receivedBetween(now()->startOfMonth(), now()->endOfMonth())
            ->sum('amount');
        $totalKeseluruhan = (float) Income::query()->sum('amount');

        $rows = Income::query()
            ->with('category')
            ->receivedBetween($start, $end)
            ->get(['id', 'category_id', 'title', 'received_at', 'amount']);

        $chart = $this->buildChart($rows, $start, $end);

        $transactions = $tampilan === 'semua'
            ? Income::query()
                ->with('category')
                ->receivedBetween($start, $end)
                ->orderByDesc('received_at')
                ->orderByDesc('id')
                ->paginate(10)
                ->withQueryString()
            : null;

        $totalsByCategory = $tampilan === 'total'
            ? $rows->groupBy('category_id')
                ->map(fn (Collection $group, int $categoryId): array => [
                    'category' => $group->first()->category,
                    'total' => $group->sum(fn (Income $income): float => (float) $income->amount),
                ])
                ->sortByDesc('total')
                ->values()
            : null;

        return view('dashboard.index', [
            'totalFiltered' => $totalFiltered,
            'totalBulanIni' => $totalBulanIni,
            'totalKeseluruhan' => $totalKeseluruhan,
            'countFiltered' => $countFiltered,
            'average' => $average,
            'chart' => $chart,
            'chartType' => $start->isSameMonth($end) ? 'bar' : 'line',
            'transactions' => $transactions,
            'totalsByCategory' => $totalsByCategory,
            'selectedMonth' => $anchor->format('Y-m'),
            'selectedRange' => $rentang,
            'selectedView' => $tampilan,
        ]);
    }

    /**
     * Build evenly spaced chart points so empty periods remain visible.
     *
     * @param  Collection<int, Income>  $rows
     * @return array<int, array{label: string, total: float}>
     */
    private function buildChart(Collection $rows, Carbon $start, Carbon $end): array
    {
        if ($start->isSameMonth($end)) {
            $points = [];

            for ($day = 1; $day <= $end->day; $day++) {
                $points[$day] = ['label' => (string) $day, 'total' => 0.0];
            }

            foreach ($rows as $income) {
                $day = $income->received_at->day;

                if (isset($points[$day])) {
                    $points[$day]['total'] += (float) $income->amount;
                }
            }

            return array_values($points);
        }

        $points = [];

        for ($cursor = $start->copy()->startOfMonth(); $cursor <= $end; $cursor->addMonth()) {
            $key = $cursor->format('Y-m');
            $points[$key] = [
                'label' => self::MONTH_LABELS[$cursor->month - 1].' '.$cursor->year,
                'total' => 0.0,
            ];
        }

        foreach ($rows as $income) {
            $key = $income->received_at->format('Y-m');

            if (isset($points[$key])) {
                $points[$key]['total'] += (float) $income->amount;
            }
        }

        return array_values($points);
    }
}
