<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_display_all_tags()
    {
        Tag::factory(5)->create();

        $response = $this->getJson('/api/tags');

        $response
            ->assertJsonStructure(['data']);
    }
}
