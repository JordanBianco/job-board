<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            'PHP',
            'Javascript',
            'Laravel',
            'Vue',
            'Git',
            'Web development',
            'Angular',
            'React',
            'Django',
            'MYSQL'
        ];

        foreach ($tags as $tag) {
            Tag::factory()->create([
                'name' => $tag,
                'slug' => Str::slug($tag)
            ]);
        }
    }
}
