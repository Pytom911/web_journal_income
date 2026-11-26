<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Models\Category;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'bulan' => ['nullable', 'date_format:Y-m'],
        ]);

        $query = Income::query()
            ->with('category')
            ->orderByDesc('received_at')
            ->orderByDesc('id');

        if (isset($validated['bulan'])) {
            $start = Carbon::createFromFormat('Y-m', $validated['bulan'])->startOfMonth();

            $query->receivedBetween($start, $start->copy()->endOfMonth());
        }

        $totalQuery = clone $query;
        $incomes = $query->paginate(15)->withQueryString();
        $totalFiltered = (float) $totalQuery->sum('amount');

        return view('incomes.index', [
            'incomes' => $incomes,
            'totalFiltered' => $totalFiltered,
            'selectedMonth' => $validated['bulan'] ?? '',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('incomes.create', [
            'income' => new Income,
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncomeRequest $request): RedirectResponse
    {
        Income::query()->create($request->validated());

        return redirect()->route('incomes.index')->with('success', 'Income berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Income $income): View
    {
        return view('incomes.edit', [
            'income' => $income,
            'categories' => Category::query()
                ->where('is_active', true)
                ->orWhere('id', $income->category_id)
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIncomeRequest $request, Income $income): RedirectResponse
    {
        $income->update($request->validated());

        return redirect()->route('incomes.index')->with('success', 'Income berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income): RedirectResponse
    {
        $income->delete();

        return redirect()->route('incomes.index')->with('success', 'Income berhasil dihapus.');
    }
}
