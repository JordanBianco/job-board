<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Job;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ContractSeeder::class);
        $this->call(TagSeeder::class);

        $tags = Tag::all();

        $locations = ['Roma', 'Milano', 'Bologna', 'Torino'];
        $positions = ['Full Stack Developer', 'Backend Developer', 'Frontend Developer', 'UX Designer', 'Senior PHP Developer'];

        User::factory(20)->create()->each(function($user) use($tags, $locations, $positions) {
            Job::factory(rand(5, 10))->create([
                'user_id' => $user->id,
                'contract_id' => Contract::all()->random()->id,
                'location' => Arr::random($locations),
                'position' => Arr::random($positions)
            ])
            ->each(function($job) use($tags) {
                $job->tags()->attach($tags->random(2));
            });
        });
    }
}
