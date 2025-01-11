<?php

namespace Tests\Feature;

use App\Models\InventoryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InventoryItemTest extends TestCase
{
    public $url = 'api/inventory-item/';
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_item()
    {
        $response = $this->get($this->url.'all');
        $response->assertStatus(200);
    }

    public function test_create_item_validation() : void
    {
        $response = $this->postJson($this->url.'create');
        $response->assertStatus(422);
        $response->assertJsonFragment([
            'ok' => false,
            'msg' => "Creating item failed",
        ]);
    }

    public function test_create_inventory_item() : void
    {
        $response = $this->postJson($this->url.'create',[
            "item_desc" => "Mangoes and carrrotes"
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'ok' => true,
            'msg' => "Inventory item created",
        ]);
    }


    public function test_update_validation_inventory_item(): void
    {
        $response = $this->postJson($this->url.'update',[
            'item_code' => '93949340040',
            "item_desc" => "Oranges and coconut",
        ]);
        $response->assertStatus(422);
        $response->assertJsonFragment([
            'ok' => false,
            'errors_all' => ['Item code is invalid'],
        ]);
       
    }


    public function test_update_inventory_item(): void
    {
        $response = $this->postJson($this->url.'update',[
            'item_code' => 'INV-ITM-00000',
            "item_desc" => "Oranges and Land Boy",
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'ok' => true,
        ]);
       
    }


    public function test_delete_inventory_item(): void
    {
        $response = $this->postJson($this->url.'delete',[
            'item_code' => 'INV-ITM-00000',
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'ok' => true,
        ]);

        $this->assertDatabaseHas(InventoryItem::class,[
            'item_code' => 'INV-ITM-00000',
            'deleted'  =>  1,
        ]);
       
    }
}
