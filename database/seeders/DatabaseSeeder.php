<?php

namespace Database\Seeders;

use App\Models\MaterialCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (['Raw material', 'Finish goods', 'Spares', 'Machines', 'Others'] as $categoryName) {
            MaterialCategory::firstOrCreate(['name' => $categoryName]);
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
