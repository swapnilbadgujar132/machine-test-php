<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\MaterialCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_material_current_balance_is_calculated_from_opening_balance_and_movements(): void
    {
        $category = MaterialCategory::create([
            'name' => 'Raw material',
        ]);

        $material = Material::create([
            'category_id' => $category->id,
            'name' => 'Cement',
            'opening_balance' => 10.50,
        ]);

        $material->movements()->create([
            'movement_date' => '2026-09-30',
            'quantity' => 2.25,
        ]);

        $material->movements()->create([
            'movement_date' => '2026-10-01',
            'quantity' => -3.75,
        ]);

        $this->assertSame('9.00', number_format($material->fresh()->current_balance, 2, '.', ''));
    }

    public function test_materials_index_page_loads(): void
    {
        $response = $this->get('/materials');

        $response->assertStatus(200);
    }

    public function test_material_can_be_created_and_soft_deleted(): void
    {
        $category = MaterialCategory::create(['name' => 'Raw material']);

        $response = $this->post(route('materials.store'), [
            'category_id' => $category->id,
            'name' => 'Portland Cement',
            'opening_balance' => '25.50',
        ]);

        $material = Material::query()->firstOrFail();

        $response->assertRedirect(route('materials.index'));
        $this->assertSame('Portland Cement', $material->name);
        $this->assertSame('25.50', number_format((float) $material->opening_balance, 2, '.', ''));
        $this->get(route('materials.index'))->assertSee('MAT-00001')->assertSee('25.50');

        $this->delete(route('materials.destroy', $material))
            ->assertRedirect(route('materials.index'));

        $this->assertSoftDeleted($material);
    }

    public function test_signed_movement_is_saved_and_must_match_the_selected_category(): void
    {
        $category = MaterialCategory::create(['name' => 'Raw material']);
        $otherCategory = MaterialCategory::create(['name' => 'Spares']);
        $material = Material::create([
            'category_id' => $category->id,
            'name' => 'Cement',
            'opening_balance' => '10.00',
        ]);

        $this->post(route('material-movements.store'), [
            'category_id' => $category->id,
            'material_id' => $material->id,
            'movement_date' => '2026-09-30',
            'quantity' => '-3.25',
        ])->assertRedirect(route('material-movements.index'));

        $this->assertDatabaseHas('material_movements', [
            'material_id' => $material->id,
            'quantity' => '-3.25',
        ]);
        $this->assertSame('6.75', number_format($material->fresh()->current_balance, 2, '.', ''));

        $this->from(route('material-movements.create'))
            ->post(route('material-movements.store'), [
                'category_id' => $otherCategory->id,
                'material_id' => $material->id,
                'movement_date' => '2026-09-30',
                'quantity' => '2.00',
            ])
            ->assertSessionHasErrors('material_id');

        $this->assertDatabaseCount('material_movements', 1);
    }

    public function test_category_can_be_created_updated_and_deleted(): void
    {
        $this->post(route('material-categories.store'), ['name' => 'Machines'])
            ->assertRedirect(route('material-categories.index'));

        $category = MaterialCategory::query()->firstOrFail();

        $this->put(route('material-categories.update', $category), ['name' => 'Equipment'])
            ->assertRedirect(route('material-categories.index'));
        $this->assertSame('Equipment', $category->fresh()->name);

        $this->delete(route('material-categories.destroy', $category))
            ->assertRedirect(route('material-categories.index'));
        $this->assertSoftDeleted($category);
    }

    public function test_category_and_movement_pages_load(): void
    {
        $category = MaterialCategory::create(['name' => 'Raw material']);
        $material = Material::create([
            'category_id' => $category->id,
            'name' => 'Cement',
            'opening_balance' => '10.00',
        ]);

        $this->get(route('material-categories.index'))->assertOk()->assertSee('Raw material');
        $this->get(route('material-categories.create'))->assertOk();
        $this->get(route('material-categories.edit', $category))->assertOk();
        $this->get(route('materials.create'))->assertOk();
        $this->get(route('materials.edit', $material))->assertOk();
        $this->get(route('material-movements.create'))->assertOk()->assertSee('Positive quantity adds stock');
        $this->get(route('material-movements.index'))->assertOk();
    }

    public function test_movement_ledger_retains_history_after_material_and_category_are_deleted(): void
    {
        $category = MaterialCategory::create(['name' => 'Raw material']);
        $material = Material::create([
            'category_id' => $category->id,
            'name' => 'Cement',
            'opening_balance' => '10.00',
        ]);
        $material->movements()->create([
            'movement_date' => '2026-09-30',
            'quantity' => '-2.00',
        ]);

        $material->delete();
        $category->delete();

        $this->get(route('material-movements.index'))
            ->assertOk()
            ->assertSee('Cement')
            ->assertSee('Raw material')
            ->assertSee('-2.00');
    }
}
