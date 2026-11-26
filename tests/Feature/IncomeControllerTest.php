<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Income;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class IncomeControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_index_lists_all_incomes_when_no_month_is_selected(): void
    {
        $category = Category::factory()->create();

        Income::factory()->for($category)->create([
            'title' => 'Income Januari',
            'received_at' => '2026-01-15',
            'amount' => 100,
        ]);
        Income::factory()->for($category)->create([
            'title' => 'Income Februari',
            'received_at' => '2026-02-15',
            'amount' => 200,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('incomes.index'));

        $response->assertOk()
            ->assertSee('Income Januari')
            ->assertSee('Income Februari');
        $response->assertViewHas('selectedMonth', '');
        $response->assertViewHas('totalFiltered', fn (float $total): bool => $total === 300.0);
    }

    public function test_index_filters_incomes_by_selected_month(): void
    {
        $category = Category::factory()->create();

        Income::factory()->for($category)->create([
            'title' => 'Gaji Januari',
            'received_at' => '2026-01-10',
            'amount' => 100,
        ]);
        Income::factory()->for($category)->create([
            'title' => 'Gaji Februari',
            'received_at' => '2026-02-10',
            'amount' => 200,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('incomes.index', ['bulan' => '2026-01']));

        $response->assertOk()
            ->assertSee('Gaji Januari')
            ->assertDontSee('Gaji Februari');
        $response->assertViewHas('totalFiltered', fn (float $total): bool => $total === 100.0);
    }

    public function test_store_creates_income_from_valid_payload(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->post(route('incomes.store'), [
                'title' => 'Gaji Juni',
                'category_id' => $category->id,
                'amount' => '1500000',
                'received_at' => '2026-06-01',
                'source' => 'PT Contoh',
                'note' => 'Catatan gaji',
            ]);

        $response->assertRedirect(route('incomes.index'))
            ->assertSessionHas('success', 'Income berhasil ditambahkan.');
        $this->assertDatabaseHas('incomes', [
            'category_id' => $category->id,
            'title' => 'Gaji Juni',
            'source' => 'PT Contoh',
        ]);

        $income = Income::query()->where('title', 'Gaji Juni')->firstOrFail();

        $this->assertSame('1500000.00', $income->amount);
        $this->assertSame('2026-06-01', $income->received_at->toDateString());
    }

    public function test_store_rejects_inactive_category(): void
    {
        $category = Category::factory()->create(['is_active' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('incomes.store'), [
                'title' => 'Income tidak valid',
                'category_id' => $category->id,
                'amount' => '100000',
                'received_at' => '2026-06-01',
            ]);

        $response->assertSessionHasErrors([
            'category_id' => 'Kategori yang dipilih tidak tersedia.',
        ]);
        $this->assertDatabaseCount('incomes', 0);
    }

    public function test_update_changes_income_details(): void
    {
        $category = Category::factory()->create();
        $income = Income::factory()->for($category)->create([
            'title' => 'Judul lama',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('incomes.update', $income), [
                'title' => 'Judul baru',
                'category_id' => $category->id,
                'amount' => '250000',
                'received_at' => '2026-06-02',
            ]);

        $response->assertRedirect(route('incomes.index'))
            ->assertSessionHas('success', 'Income berhasil diperbarui.');

        $income->refresh();

        $this->assertSame('Judul baru', $income->title);
        $this->assertSame('250000.00', $income->amount);
    }

    public function test_update_keeps_inactive_category_attached_to_existing_income(): void
    {
        $category = Category::factory()->create(['is_active' => false]);
        $income = Income::factory()->for($category)->create([
            'title' => 'Riwayat lama',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('incomes.update', $income), [
                'title' => 'Riwayat diperbarui',
                'category_id' => $category->id,
                'amount' => '300000',
                'received_at' => '2026-06-03',
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('incomes.index'));
        $this->assertSame($category->id, $income->fresh()->category_id);
    }

    public function test_destroy_deletes_income(): void
    {
        $income = Income::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('incomes.destroy', $income));

        $response->assertRedirect(route('incomes.index'));
        $this->assertModelMissing($income);
    }

    public function test_create_form_hides_inactive_categories(): void
    {
        Category::factory()->create(['name' => 'Kategori Aktif']);
        Category::factory()->create([
            'name' => 'Kategori Nonaktif',
            'is_active' => false,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('incomes.create'));

        $response->assertOk()
            ->assertSee('Kategori Aktif')
            ->assertDontSee('Kategori Nonaktif');
    }

    public function test_index_escapes_user_provided_income_title(): void
    {
        Income::factory()->create([
            'title' => '<script>alert(1)</script>',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('incomes.index'));

        $response->assertOk()
            ->assertDontSeeHtml('<script>alert(1)</script>')
            ->assertSeeHtml('&lt;script&gt;alert(1)&lt;/script&gt;');
    }
}
