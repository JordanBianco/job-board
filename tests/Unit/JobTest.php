<?php

namespace Tests\Unit;

use App\Models\Contract;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_advertisement_belongs_to_user()
    {
        Contract::factory()->create();
        User::factory()->create();
        $job = Job::factory()->create();

        $this->assertInstanceOf(User::class, $job->user);
    }

    public function test_job_advertisement_belongs_to_contract_type()
    {
        Contract::factory()->create();
        User::factory()->create();
        $job = Job::factory()->create();

        $this->assertInstanceOf(Contract::class, $job->contract);
    }

    public function test_job_advertisement_belongs_to_many_tags()
    {
        Contract::factory()->create();
        User::factory()->create();
        $job = Job::factory()->create();

        $this->assertInstanceOf(Collection::class, $job->tags);
    }
}
