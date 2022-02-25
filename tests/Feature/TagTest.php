<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_display_all_tags()
    {
        Tag::factory(5)->create();

        $response = $this->get('/api/tags');

        $response
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json->has('data', 5);
            });
    }
}
