<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Item $item;

    protected Category $category;

    protected Unit $unit;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $category = Category::create([
            'name' => 'Test Category',
            'short_name' => 'TC',
            'description' => 'Test Description',
            'active' => true,
        ]);

        $unit = Unit::create([
            'name' => 'Test Unit',
            'short_name' => 'TU',
            'active' => true,
        ]);

        $item = Item::create([

            'hsn_code' => 'HSN123',
            'name' => 'Test Item',
            'rate' => 100.00,
            'additional_cost' => 5.00,
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'description' => 'Test Description',
            'status' => '1',
        ]);

        $this->category = $category;
        $this->unit = $unit;
        $this->item = $item;
    }

    public function test_can_list_items()
    {
        $item = $this->item;
        $response = $this->actingAs($this->user)->get(route('item.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.item.index');
        $response->assertViewHas('ajaxUrl');
    }

    public function test_can_create_item()
    {
        $response = $this->actingAs($this->user)->post(route('item.store'), [
            'hsn_code' => 'HSN0001',
            'name' => 'Item',
            'category' => $this->category->id,
            'unit' => $this->unit->id,
            'rate' => 50000.00,
            'additional_cost' => 10.00,
            'description' => 'Test Description',
            'status' => '1',
        ]);

        $response->assertRedirect(route('item.index'));

        $this->assertDatabaseHas('items', [
            'hsn_code' => 'HSN0001',
            'name' => 'Item',
        ]);
    }

    public function test_can_update_item()
    {
        $item = $this->item;

        $response = $this->actingAs($this->user)->put(route('item.update', $item), [
            'hsn_code' => 'UPD0001',
            'name' => 'Updated Item',
            'category' => $this->category->id,
            'unit' => $this->unit->id,
            'rate' => 60000.00,
            'additional_cost' => 15.00,
            'description' => 'Updated Description',
            'status' => '1',
        ]);

        $response->assertRedirect(route('item.index'));

        $this->assertDatabaseHas('items', [
            'hsn_code' => 'UPD0001',
            'name' => 'Updated Item',
        ]);
    }

    public function test_can_delete_item()
    {
        $item = $this->item;

        $response = $this->actingAs($this->user)->delete(route('item.destroy', $item));

        $response->assertStatus(200);

        $response->assertJson([
            'status' => 'success',
        ]);

        $this->assertDatabaseMissing('items', [
            'id' => $item->id,
        ]);
    }
}
