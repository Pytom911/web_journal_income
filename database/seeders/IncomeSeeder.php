<?php

namespace Database\Seeders;

use App\Models\Income;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    /**
     * Seed contoh income untuk pengembangan lokal.
     *
     * Tidak dipanggil dari DatabaseSeeder agar data jurnal asli tidak tercampur.
     * Jalankan secara eksplisit dengan: php artisan db:seed --class=IncomeSeeder
     */
    public function run(): void
    {
        Income::factory()->count(12)->create();
    }
}
