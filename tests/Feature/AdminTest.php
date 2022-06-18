<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    public function test_login_token(){
        $this->withoutExceptionHandling();
        $response = $this->post('/api/CreateItem', [
            'name' => 'rice',
            'unit_price' => '40'
        ]);
        $response->assertStatus(201);
        $this->assertTrue(count(Item::all()) > 1);
    }

    public function test_revoke_token(){
        $this->withoutExceptionHandling();
        $response = $this->patch("/api/UpdateItem", [
            'id' => '1',
            'unit_price' => '40',
            'name' => 'rice'
        ]);
        $this->assertTrue(Item::findOrFail(1));
        $response->assertStatus(200);
    }

    public function test_delete_item(){
        
        $this->withoutExceptionHandling();
        $this->delete("/api/DeleteItem",[
            'id' => '1',
        ]); 
        $this->assertDatabaseMissing('Item', ['id' => 1]); 
    }
}
