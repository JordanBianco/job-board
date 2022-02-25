<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_post_many_job_advertisements()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->jobs);
    }
}
