<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();

        User::factory()->create();
    }

    public function test_can_view_job_list()
    {
        Job::factory(5)->create();

        $response = $this->get('/api/jobs');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('links')
                    ->has('meta')
                    ->has('data', 5)
                    ->has('data.0', function($json) {
                        $json
                            ->where('id', 1)
                            ->where('tags', [])
                            ->etc();
                    });
            });
    }

    public function test_can_view_only_approved_jobs()
    {        
        Job::factory(2)->create();

        Job::factory()->create(['is_approved' => false ]);

        $response = $this->get('/api/jobs');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('links')
                    ->has('meta')
                    ->has('data', 2)
                    ->has('data.0', function($json) {
                        $json
                            ->where('is_approved', true)
                            ->etc();
                    });
            });
    }

    public function test_can_view_a_single_job()
    {
        $job = Job::factory()->create(['title' => 'test'])->first();

        $response = $this->get('/api/jobs/' . $job->slug);

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json->has('data', function($json) {
                    $json
                        ->where('id', 1)
                        ->where('title', 'test')
                        ->etc();
                });
            });
    }
}
