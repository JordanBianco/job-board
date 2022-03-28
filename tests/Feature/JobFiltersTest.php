<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Job;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class JobFiltersTest extends TestCase
{
    use LazilyRefreshDatabase;

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

        $response = $this->getJson('/api/jobs?contract=1');

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

        $response = $this->getJson('/api/jobs?contract=1,2');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 4);
            });
    }

    public function test_filter_jobs_by_working_day()
    {
        Job::factory()->create(['working_day' => 'full-time' ]);
        Job::factory()->create(['working_day' => 'part-time' ]);
        Job::factory()->create(['working_day' => 'part-time' ]);

        $response = $this->getJson('/api/jobs?working_day=full-time');

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

    public function test_filter_jobs_by_remote_working()
    {
        Job::factory()->create(['remote_working' => 1 ]);
        Job::factory()->create(['remote_working' => 0 ]);

        $response = $this->getJson('/api/jobs?remote_working=1');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 1)
                    ->has('data.0', function($json) {
                        $json
                            ->where('remote_working', true)
                            ->etc();
                });
            });
    }

    public function test_filter_jobs_by_remote_working_false_value()
    {
        Job::factory()->create(['remote_working' => 1 ]);
        Job::factory()->create(['remote_working' => 0 ]);

        $response = $this->getJson('/api/jobs?remote_working=0');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 1)
                    ->has('data.0', function($json) {
                        $json
                            ->where('remote_working', false)
                            ->etc();
                });
            });
    }

    public function test_filter_jobs_by_tags()
    {
        Tag::factory(2)->create();

        $job = Job::factory()->create(['title' => 'php']);
        $job2 = Job::factory()->create(['title' => 'js']);

        $job->tags()->attach(1);
        $job2->tags()->attach(2);

        $response = $this->getJson('/api/jobs?tags=1');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 1)
                    ->has('data.0', function($json) {
                        $json
                            ->where('title', 'php')
                            ->etc();
                });
            });
    }

    public function test_filter_jobs_by_many_tags()
    {
        Tag::factory(3)->create();

        $job = Job::factory()->create(['title' => 'php']);
        $job2 = Job::factory()->create(['title' => 'laravel']);
        $job3 = Job::factory()->create(['title' => 'js']);

        $job->tags()->attach(1);
        $job2->tags()->attach(2);
        $job3->tags()->attach(3);

        $response = $this->getJson('/api/jobs?tags=1,2');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 2)
                    ->has('data.0', function($json) {
                        $json
                            ->where('title', 'php')
                            ->etc();
                    })
                    ->has('data.1', function($json) {
                        $json
                            ->where('title', 'laravel')
                            ->etc();
                    });
            });
    }
}
