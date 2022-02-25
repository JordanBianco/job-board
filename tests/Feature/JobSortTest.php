<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobSortTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();

        Contract::factory()->create();
        User::factory()->create();
    }

    public function test_sort_job()
    {
        Job::factory()->create([
            'title' => 'latest',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Job::factory()->create([
            'title' => 'oldest',
            'created_at' => now()->subHour(),
            'updated_at' => now()->subHour()
        ]);

        $response = $this->get('/api/jobs?sort=oldest');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 2)
                    ->has('data.0', function($json) {
                        $json
                            ->where('title', 'oldest')
                            ->etc();
                });
            });
    }
}
