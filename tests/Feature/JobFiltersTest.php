<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();

        Contract::factory(3)->create();
        User::factory()->create();
    }

    public function test_filter_jobs_by_contract_type()
    {
        Job::factory()->create(['contract_id' => 1 ]);
        Job::factory()->create(['contract_id' => 1 ]);
        Job::factory()->create(['contract_id' => 2 ]);
        Job::factory()->create(['contract_id' => 2 ]);

        $response = $this->get('/api/jobs?contract=1');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 2)
                    ->has('data.0', function($json) {
                        $json
                            ->where('contract_id', 1)
                            ->etc();
                });
            });
    }

    public function test_filter_jobs_by_many_contract_type()
    {
        Job::factory()->create(['contract_id' => 1 ]);
        Job::factory()->create(['contract_id' => 1 ]);
        Job::factory()->create(['contract_id' => 2 ]);
        Job::factory()->create(['contract_id' => 2 ]);
        Job::factory()->create(['contract_id' => 3 ]);

        $response = $this->get('/api/jobs?contract=1,2');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 4)
                    ->has('data.0', function($json) {
                        $json
                            ->where('contract_id', 1)
                            ->etc();
                });
            });
    }

    public function test_filter_jobs_by_working_day()
    {
        Job::factory()->create(['working_day' => 'full-time' ]);
        Job::factory()->create(['working_day' => 'part-time' ]);
        Job::factory()->create(['working_day' => 'part-time' ]);

        $response = $this->get('/api/jobs?working_day=full-time');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 1)
                    ->has('data.0', function($json) {
                        $json
                            ->where('working_day', 'full-time')
                            ->etc();
                });
            });
    }
}
