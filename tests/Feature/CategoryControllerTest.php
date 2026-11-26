<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Income;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_index_shows_category_status_and_income_count(): void
    {
        $usedCategory = Category::factory()->create([
            'name' => 'Gaji',
            'is_active' => true,
        ]);
        Income::factory()->for($usedCategory)->count(2)->create();

        Category::factory()->create([
            'name' => 'Bonus',
            'is_active' => false,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('categories.index'));

        $response->assertOk()
            ->assertSee('Gaji')
            ->assertSee('2 transaksi')
            ->assertSee('Aktif')
            ->assertSee('Nonaktif');
    }

    public function test_store_creates_category_with_generated_slug(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('categories.store'), [
                'name' => 'Honorarium',
                'color' => '#06B6D4',
                'description' => 'Pembayaran proyek',
                'is_active' => 1,
            ]);

        $response->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Kategori berhasil ditambahkan.');
        $this->assertDatabaseHas('categories', [
            'name' => 'Honorarium',
            'slug' => 'honorarium',
            'color' => '#06B6D4',
            'is_active' => true,
        ]);
    }

    public function test_store_rejects_duplicate_category_name(): void
    {
        Category::factory()->create(['name' => 'Gaji']);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('categories.store'), [
                'name' => 'Gaji',
                'is_active' => 1,
            ]);

        $response->assertSessionHasErrors([
            'name' => 'Kategori dengan nama ini sudah ada.',
        ]);
        $this->assertDatabaseCount('categories', 1);
    }

    public function test_update_allows_category_to_keep_its_own_name(): void
    {
        $category = Category::factory()->create(['name' => 'Gaji']);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('categories.update', $category), [
                'name' => 'Gaji',
                'is_active' => 1,
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Gaji',
        ]);
    }

    public function test_update_generates_unique_slug_when_slug_is_taken(): void
    {
        Category::factory()->create([
            'name' => 'Gaji Lain Lama',
            'slug' => 'gaji-lain',
        ]);
        $category = Category::factory()->create([
            'name' => 'Gaji Baru',
            'slug' => 'gaji-baru',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('categories.update', $category), [
                'name' => 'Gaji Lain',
                'color' => '#0891b2',
                'is_active' => 1,
            ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Gaji Lain',
            'slug' => 'gaji-lain-2',
        ]);
    }

    public function test_destroy_deletes_category_without_incomes(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertModelMissing($category);
    }

    public function test_destroy_is_blocked_when_category_has_incomes(): void
    {
        $category = Category::factory()->create();
        Income::factory()->for($category)->create();

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('categories.destroy', $category));

        $response->assertRedirect()
            ->assertSessionHas('error', 'Kategori masih digunakan. Nonaktifkan saja agar riwayat income tetap tersimpan.');
        $this->assertModelExists($category);
    }

    public function test_toggle_status_deactivates_category_with_incomes(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        Income::factory()->for($category)->create();

        $response = $this->actingAs(User::factory()->create())
            ->patch(route('categories.toggle-status', $category));

        $response->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Kategori berhasil dinonaktifkan.');
        $this->assertFalse($category->fresh()->is_active);
    }

    public function test_toggle_status_activates_inactive_category_with_incomes(): void
    {
        $category = Category::factory()->create(['is_active' => false]);
        Income::factory()->for($category)->create();

        $response = $this->actingAs(User::factory()->create())
            ->patch(route('categories.toggle-status', $category));

        $response->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Kategori berhasil diaktifkan.');
        $this->assertTrue($category->fresh()->is_active);
    }

    public function test_index_escapes_user_provided_category_name(): void
    {
        Category::factory()->create([
            'name' => '<script>alert(1)</script>',
            'slug' => 'xss-kategori',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('categories.index'));

        $response->assertOk()
            ->assertDontSeeHtml('<script>alert(1)</script>')
            ->assertSeeHtml('&lt;script&gt;alert(1)&lt;/script&gt;');
    }
}
