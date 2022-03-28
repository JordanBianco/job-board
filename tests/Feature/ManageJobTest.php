<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Job;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ManageJobTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_cannot_post_a_job()
    {
        $this->postJson('/api/jobs')->assertUnauthorized();
    }

    public function test_auth_user_can_post_a_job_adv()
    {
        Tag::factory(4)->create();
        Contract::factory(2)->create();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/jobs', [
            'user_id' => 1,
            'contract_id' => 1,
            'position' => 'test position',
            'location' => 'test location',
            'remote_working' => false,
            'working_day' => 'full-time',
            'company' => 'testcompany',
            'logo' => null,
            'title' => 'test title',
            'description' => 'test description',
            'salary' => '50000 - 70000',
            'apply_link' => 'http://google.com',
            'tags' => '1, 2',
            'is_approved' => false
        ]);

        $response->assertStatus(201);

        $this->assertEquals(1, $user->fresh()->jobs->count());
        $this->assertDatabaseCount('jobs', 1);
    }

    public function test_auth_user_can_add_an_image_to_the_job_adv()
    {
        Storage::fake('public');

        Tag::factory(4)->create();
        Contract::factory(1)->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->image('image.jpg');

        $response = $this->postJson('/api/jobs', [
            'contract_id' => 1,
            'position' => 'test position',
            'location' => 'test location',
            'remote_working' => true,
            'working_day' => 'full-time',
            'company' => 'testcompany',
            'logo' => $file,
            'title' => 'test title',
            'description' => 'test description',
            'salary' => '50000 - 70000',
            'apply_link' => 'http://google.com',
            'tags' => '1,2',
            'is_approved' => false
        ]);

        $response->assertStatus(201);

        Storage::disk('public')->assertExists($file->hashName());
    }

    public function test_can_update_a_job()
    {
        Contract::factory(1)->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $job = Job::factory()->for($user)->create()->first();

        $response = $this->patchJson('/api/jobs/' . $job->id . '/update', [
            'contract_id' => $job->contract->id,
            'position' => $job->position,
            'location' => $job->location,
            'remote_working' => $job->remote_working,
            'working_day' => $job->working_day,
            'company' => $job->company,
            'logo' => null,
            'title' => 'updated',
            'description' => $job->description,
            'salary' => $job->salary,
            'apply_link' => $job->apply_link,
            'tags' => $job->tags,
            'is_approved' => $job->is_approved,
		]);

		$response->assertStatus(200);

		$this->assertEquals('updated', $job->fresh()->title);
		$this->assertEquals('updated', $job->fresh()->slug);
    }

    public function test_user_cannot_update_others_job_adv()
    {
        Contract::factory(1)->create();
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user);

        $job = Job::factory()->for($user2)->create()->first();

        $this->patchJson('/api/jobs/' . $job->id . '/update', [
            'contract_id' => $job->contract->id,
            'position' => $job->position,
            'location' => $job->location,
            'remote_working' => $job->remote_working,
            'working_day' => $job->working_day,
            'company' => $job->company,
            'logo' => null,
            'title' => 'updated',
            'description' => $job->description,
            'salary' => $job->salary,
            'apply_link' => $job->apply_link,
            'tags' => $job->tags,
            'is_approved' => $job->is_approved,
		])
        ->assertStatus(403);
    }

    public function test_can_delete_a_job()
    {
        Contract::factory(1)->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $job = Job::factory()->for($user)->create()->first();

        $this->deleteJson('api/jobs/' . $job->id . '/delete');

        $this->assertDatabaseMissing('jobs', $job->only('id'));
        $this->assertDatabaseCount('jobs', 0);
    }

    public function test_cannot_delete_others_job_adv()
    {
        Contract::factory(1)->create();
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user);

        $job = Job::factory()->for($user2)->create()->first();

        $this->deleteJson('api/jobs/' . $job->id . '/delete')
            ->assertStatus(403);

        $this->assertDatabaseHas('jobs', $job->only('id'));
        $this->assertDatabaseCount('jobs', 1);
    }
}