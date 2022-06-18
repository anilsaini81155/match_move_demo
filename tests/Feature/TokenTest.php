<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;

class TokenTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    public function test_create_token(){
        $this->withoutExceptionHandling();
        $response = $this->post('/api/admin/CreateToken', [
            'email' => 'abc@xyz.com',
            'name' => 'apqad45',
            'mobile_no' => 7867612345
        ]);
        $response->assertStatus(201);
        
    }

    public function test_revoke_token(){
        $this->withoutExceptionHandling();
        $response = $this->patch("/api/open-call/ValidateToken", [
            'bearerToken' => 'dsadasdsajnas12jns'
        ]);
        $response->assertStatus(404);
    }

}
