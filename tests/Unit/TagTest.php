<?php

namespace Tests\Unit;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_advertisement_belongs_to_many_tags()
    {
        $tag = Tag::factory()->create();

        $this->assertInstanceOf(Collection::class, $tag->jobs);
    }
}
