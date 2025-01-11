<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\SupplierMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupplierMemberTest extends TestCase
{
    protected $url = 'api/supplier-member/';


    public function test_create_supplier_member_validation(): void
    {
        $response = $this->postJson($this->url . 'create');
        $response->assertStatus(422);
        $response->assertJsonFragment([
            'msg' =>  "Creating supplier member failed",
            'ok' => false
        ]);
    }

    public function test_create_supplier_member(): void
    {
        $response = $this->postJson($this->url . 'create', [
            'fname' => 'Roland',
            'lname' => 'Nii',
            'phone' => '0554395459',
            'supplier_id' => "e2e0d07780e766989301",
            'position' => 'Accountant',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas(SupplierMember::class, [
            'fname' => 'Roland',
            'lname' => 'Nii',
            'phone' => '0554395459',
            'supplier_id' => "e2e0d07780e766989301",
            'position' => 'Accountant',
        ]);
    }


    public function test_update_supplier_member_details(): void
    {
        $response = $this->postJson($this->url . 'update', [
            'transid' => '90fac0dbfcdc27770d4a',
            'fname' => 'Dori',
            'lname' => 'Nappi',
            'phone' => '0554395470',
            'supplier_code' => "SUP-0001",
            'position_code' => 'POS-00004',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas(SupplierMember::class, [
            'transid' => '90fac0dbfcdc27770d4a',
            'phone' => '0554395470',
            'supplier_code' => "SUP-0001",
            'position_code' => 'POS-00004',
        ]);
    }


    public function test_delete_supplier_member()
    {
        $response = $this->postJson($this->url . 'delete', [
            'transid' => 'faaa398b3daf8312a54f'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas(SupplierMember::class, [
            'fname' => 'Dori',
            'lname' => 'Nappi',
            'position' => 'Laywer',
            'deleted' => 1
        ]);
    }

    public function test_fetch_all_supplier_members(): void
    {
        $response = $this->get($this->url . 'all');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'lname' => 'Nappi',
            'full_name' => 'Dori Nappi',
            'supplier_name' => 'Patience Spinka',
            'supplier_phone' => '+1.478.700.8301',
        ]);
    }

    public function test_position_relation(): void
    {
        $response = $this->get($this->url . 'all');
        $response->assertJsonFragment([
            'position_desc' => 'Sales Representative',
        ]);
    }
}
