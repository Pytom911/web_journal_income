<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class IncomeTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_returns_only_incomes_within_the_requested_period(): void
    {
        $category = Category::factory()->create();

        $inside = Income::factory()->for($category)->create([
            'received_at' => '2026-01-15',
        ]);
        Income::factory()->for($category)->create([
            'received_at' => '2025-12-31',
        ]);

        $incomes = Income::receivedBetween(
            Carbon::parse('2026-01-01'),
            Carbon::parse('2026-01-31'),
        )->get();

        $this->assertCount(1, $incomes);
        $this->assertSame($inside->id, $incomes->first()->id);
    }

    public function test_resolves_category_and_casts_amount_to_two_decimal_places(): void
    {
        $category = Category::factory()->create();
        $income = Income::factory()->for($category)->create([
            'amount' => 1500000,
        ]);

        $this->assertTrue($income->category->is($category));
        $this->assertSame('1500000.00', $income->amount);
    }
}
