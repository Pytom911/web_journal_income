<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Gaji', 'slug' => 'gaji', 'color' => '#22C55E'],
            ['name' => 'Bonus', 'slug' => 'bonus', 'color' => '#EAB308'],
            ['name' => 'Freelance', 'slug' => 'freelance', 'color' => '#3B82F6'],
            ['name' => 'Investasi', 'slug' => 'investasi', 'color' => '#8B5CF6'],
            ['name' => 'Jualan', 'slug' => 'jualan', 'color' => '#F97316'],
            ['name' => 'Hadiah', 'slug' => 'hadiah', 'color' => '#EC4899'],
            ['name' => 'Lainnya', 'slug' => 'lainnya', 'color' => '#64748B'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                $category,
            );
        }
    }
}
