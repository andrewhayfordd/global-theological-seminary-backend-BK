<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\SupplierMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    protected $url = "api/supplier/";
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_supplier_validation(): void
    {
        $response = $this->postJson($this->url . 'create');

        $response->assertStatus(422);
        $response->assertJsonFragment(['msg' => "Creating supplier failed"]);
    }

    public function test_create_supplier(): void
    {
        $response  = $this->postJson($this->url . 'create', [
            'name' => "Book shop supplier",
            'phone' => "0557373684",
            'email' => "ror@gmail.com",
            'address' => "Adc circle"
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas(Supplier::class, [
            'name' => "Book shop supplier",
            'phone' => "0557373684",
            'email' => "ror@gmail.com",
            'address' => "Adc circle"
        ]);
    }

    public function test_update_supplier_details(): void
    {
        $response  = $this->postJson($this->url . 'update', [
            'transid' => "9cc6151a3756b1d81e67",
            'name' => "Roland shop supplier",
            'phone' => "0557373684",
            'email' => "update@gmail.com",
            'address' => "Adc circle"
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas(Supplier::class, [
            'name' => "Roland shop supplier",
            'phone' => "0557373684",
            'email' => "update@gmail.com",
            'address' => "Adc circle"
        ]);
    }

    public function test_delete_supplier_details(): void
    {
        $response = $this->postJson($this->url . 'delete', [
            'transid' => "9cc6151a3756b1d81e67",
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas(Supplier::class, [
            'deleted' => 1,
            'transid' => "9cc6151a3756b1d81e67",
        ]);
    }

    public function test_fetch_all_suppliers(): void
    {
        $response = $this->get($this->url.'all');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'transid' => 'b44d7772f4918e4e2e79',
            'name' => "Book shop supplier",
            'phone' => "0557373684",
            'email' => 'ror@gmail.com',
            'address' => 'Adc circle'
        ]);
    }

    
}
