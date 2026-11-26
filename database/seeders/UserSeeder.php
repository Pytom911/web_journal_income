<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the single journal owner account.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => config('journal.owner.email')],
            [
                'name' => config('journal.owner.name'),
                'password' => Hash::make(config('journal.owner.password')),
                'email_verified_at' => now(),
            ],
        );
    }
}
