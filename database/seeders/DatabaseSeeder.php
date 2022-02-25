<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Job;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

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

        User::factory(10)->create()->each(function($user) use($tags) {
            Job::factory(rand(1, 4))->create([
                'user_id' => $user->id,
                'contract_id' => Contract::all()->random()->id
            ])
            ->each(function($job) use($tags) {
                $job->tags()->attach($tags->random(2));
            });
        });
    }
}
