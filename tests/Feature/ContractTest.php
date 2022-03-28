<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ContractTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_display_all_types_of_contract_and_the_related_approved_jobs_count()
    {
        User::factory()->create();

        // Un contratto ha 2 lavori, di cui 1 non approvato dagli admin
        Contract::factory()->create();

        Job::factory()->create([
            'contract_id' => 1,
            'is_approved' => true
        ]);
        Job::factory()->create([
            'contract_id' => 1,
            'is_approved' => false
        ]);

        $response = $this->getJson('/api/contracts');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json->has('data', 1);
            });
    }
}
