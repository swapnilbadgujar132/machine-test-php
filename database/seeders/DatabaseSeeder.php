<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialCategory;
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
        $categories = [];

        foreach (['Raw material', 'Finish goods', 'Spares', 'Machines', 'Others'] as $categoryName) {
            $categories[$categoryName] = MaterialCategory::firstOrCreate(['name' => $categoryName]);
        }

        $demoMaterials = [
            [
                'category' => 'Raw material',
                'name' => 'Portland Cement',
                'opening_balance' => '125.00',
                'movements' => [['2026-09-28', '25.00'], ['2026-09-29', '-10.00']],
            ],
            [
                'category' => 'Finish goods',
                'name' => 'Concrete Block',
                'opening_balance' => '80.00',
                'movements' => [['2026-09-28', '-12.00'], ['2026-09-30', '20.00']],
            ],
            [
                'category' => 'Spares',
                'name' => 'Fastener Pack',
                'opening_balance' => '40.00',
                'movements' => [['2026-09-29', '-5.00']],
            ],
        ];

        foreach ($demoMaterials as $demoMaterial) {
            $material = Material::firstOrCreate(
                [
                    'category_id' => $categories[$demoMaterial['category']]->id,
                    'name' => $demoMaterial['name'],
                ],
                ['opening_balance' => $demoMaterial['opening_balance']],
            );

            foreach ($demoMaterial['movements'] as [$movementDate, $quantity]) {
                $movementExists = $material->movements()
                    ->whereDate('movement_date', $movementDate)
                    ->exists();

                if (! $movementExists) {
                    $material->movements()->create([
                        'movement_date' => $movementDate,
                        'quantity' => $quantity,
                    ]);
                }
            }
        }
    }
}
