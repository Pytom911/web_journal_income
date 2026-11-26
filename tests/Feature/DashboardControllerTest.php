<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Income;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_dashboard_calculates_totals_for_selected_month(): void
    {
        $this->travelTo('2026-06-15 12:00:00');

        $category = Category::factory()->create();

        Income::factory()->for($category)->create([
            'received_at' => '2026-06-05',
            'amount' => 100,
        ]);
        Income::factory()->for($category)->create([
            'received_at' => '2026-06-10',
            'amount' => 200,
        ]);
        Income::factory()->for($category)->create([
            'received_at' => '2026-05-20',
            'amount' => 500,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard', ['bulan' => '2026-06']));

        $response->assertOk();
        $response->assertViewHas('totalFiltered', fn (float $value): bool => $value === 300.0);
        $response->assertViewHas('countFiltered', fn (int $value): bool => $value === 2);
        $response->assertViewHas('average', fn (float $value): bool => $value === 150.0);
        $response->assertViewHas('totalBulanIni', fn (float $value): bool => $value === 300.0);
        $response->assertViewHas('totalKeseluruhan', fn (float $value): bool => $value === 800.0);
    }

    public function test_dashboard_filters_three_month_range_and_builds_monthly_chart(): void
    {
        $this->travelTo('2026-06-15 12:00:00');

        $category = Category::factory()->create();

        Income::factory()->for($category)->create([
            'received_at' => '2026-01-10',
            'amount' => 100,
        ]);
        Income::factory()->for($category)->create([
            'received_at' => '2026-04-10',
            'amount' => 200,
        ]);
        Income::factory()->for($category)->create([
            'received_at' => '2026-06-10',
            'amount' => 300,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard', [
                'bulan' => '2026-06',
                'rentang' => '3',
            ]));

        $response->assertOk();
        $response->assertViewHas('totalFiltered', fn (float $value): bool => $value === 500.0);
        $response->assertViewHas('chartType', 'line');
        $response->assertViewHas('chart', function (array $points): bool {
            return count($points) === 3
                && $points[0]['label'] === 'Apr 2026'
                && $points[2]['label'] === 'Jun 2026';
        });
    }

    public function test_dashboard_uses_daily_chart_for_one_month(): void
    {
        $this->travelTo('2026-06-15 12:00:00');

        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('chartType', 'bar');
        $response->assertViewHas('chart', function (array $points): bool {
            return count($points) === 30
                && $points[0]['label'] === '1'
                && $points[29]['label'] === '30';
        });
    }

    public function test_dashboard_total_view_groups_income_by_category(): void
    {
        $this->travelTo('2026-06-15 12:00:00');

        $salary = Category::factory()->create(['name' => 'Gaji']);
        $freelance = Category::factory()->create(['name' => 'Freelance']);

        Income::factory()->for($salary)->create([
            'received_at' => '2026-06-05',
            'amount' => 300,
        ]);
        Income::factory()->for($freelance)->create([
            'received_at' => '2026-06-06',
            'amount' => 100,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard', [
                'bulan' => '2026-06',
                'tampilan' => 'total',
            ]));

        $response->assertOk();
        $response->assertViewHas('transactions', null);
        $response->assertViewHas('totalsByCategory', function ($totals): bool {
            return $totals->count() === 2
                && $totals->first()['category']->name === 'Gaji'
                && $totals->first()['total'] === 300.0;
        });
    }

    public function test_dashboard_rejects_unknown_range(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard', ['rentang' => '99']));

        $response->assertSessionHasErrors('rentang');
    }

    public function test_dashboard_escapes_user_provided_income_title(): void
    {
        $this->travelTo('2026-06-15 12:00:00');

        Income::factory()->create([
            'title' => '<script>alert(1)</script>',
            'received_at' => '2026-06-10',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard'));

        $response->assertOk()
            ->assertDontSeeHtml('<script>alert(1)</script>')
            ->assertSeeHtml('&lt;script&gt;alert(1)&lt;/script&gt;');
    }
}
