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
        $response = $this->get('/api/admin/login', [
            'email' => 'abc@xyz.com',
            'password' => 'apq@ad45'
        ]);
        $response->assertStatus(403);
        
    }

    public function test_revoke_token(){
        $this->withoutExceptionHandling();
        $response = $this->patch("/api/admin/RevokeToken", [
            'bearerToken' => 'dsadasdsajnas12jns'
        ]);
        $response->assertStatus(404);
    }

    public function test_all_token(){
        $this->withoutExceptionHandling();
        $response = $this->get('/api/admin/GetAllToken',[
            'bearerToken' => 'dsadasdsajnas12jnass'
        ]);
        $response->assertStatus(404);
    }
}
