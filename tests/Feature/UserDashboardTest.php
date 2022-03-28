<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserDashboardTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_return_all_user_jobs_adv()
    {
        Contract::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Job::factory(2)->for($user)->create();

        $response = $this->get('/api/user/jobs');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('meta')
                    ->has('links')
                    ->has('data', 2)
                    ->has('data.0', function($json) {
                        $json
                            ->where('id', Job::first()->id)
                            ->where('title', Job::first()->title)
                            ->etc();
                    });
            });
    }
}
