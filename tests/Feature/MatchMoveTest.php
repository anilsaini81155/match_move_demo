<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;

class MatchMoveTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    public function test_get_dashboard_data()
    {
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Bearer Token' => '12dasdsajnad34dasdsajna',
        ])->get('/api/matchMove/Dashboard');
        $response->assertStatus(404);
    }

  
}
