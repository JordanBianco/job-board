<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class JobSearchTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();

        Contract::factory()->create();
        User::factory()->create();
    }

    public function test_search_job_based_on_title()
    {
        Job::factory()->create([
            'title' => 'test',
            'position' => 'developer',
            'location' => 'Roma',
        ]);

        Job::factory()->create([
            'title' => 'title',
            'position' => 'designer',
            'location' => 'Como',
        ]);

        $response = $this->getJson('/api/jobs?search=test');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 1)
                    ->has('data.0', function($json) {
                        $json
                            ->where('title', 'test')
                            ->etc();
                });
            });
    }

    public function test_search_job_based_on_position()
    {
        Job::factory()->create([
            'title' => 'test',
            'position' => 'developer',
            'location' => 'Roma',
        ]);

        Job::factory()->create([
            'title' => 'title',
            'position' => 'designer',
            'location' => 'Como',
        ]);

        $response = $this->getJson('/api/jobs?search=designer');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 1)
                    ->has('data.0', function($json) {
                        $json
                            ->where('position', 'designer')
                            ->etc();
                });
            });
    }

    public function test_search_job_based_on_lcoation()
    {
        Job::factory()->create([
            'title' => 'test',
            'position' => 'developer',
            'location' => 'Roma',
        ]);

        Job::factory()->create([
            'title' => 'title',
            'position' => 'designer',
            'location' => 'Como',
        ]);

        $response = $this->getJson('/api/jobs?search=roma');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 1)
                    ->has('data.0', function($json) {
                        $json
                            ->where('location', 'Roma')
                            ->etc();
                });
            });
    }
}
