<?php

namespace Tests\Feature;

use App\Models\Contract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_display_all_types_of_contract()
    {
        Contract::factory(5)->create();

        $response = $this->get('/api/contracts');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json->has('data', 5);
            });
    }
}
