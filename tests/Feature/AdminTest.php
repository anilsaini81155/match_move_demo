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

    public function test_login_token()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api/admin-v2/login', [
            'email' => 'abc@xyz.com',
            'password' => 'apq@ad45'
        ]);
        $response->assertStatus(403);
    }

    public function test_revoke_token()
    {
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Bearer Token' => '12dasdsajnad34dasdsajna',
        ])->patch("/api/admin/RevokeToken", [
            'token' => 'dsadasdsajnas12jns'
        ]);
        $response->assertStatus(404);
    }

    public function test_all_token()
    {
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Bearer Token' => '12dasdsajnad34dasdsajna',
        ])->get('/api/admin/GetAllToken');
        $response->assertStatus(404);
    }
}
