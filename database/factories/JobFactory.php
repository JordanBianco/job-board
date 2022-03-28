<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(8);
        $slug = Str::slug($title);

        return [
            'user_id' => User::all()->random()->id,
            'contract_id' => Contract::all()->random()->id,
            'title' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph(rand(20, 30)),
            'apply_link' => $this->faker->url(),
            'position' => $this->faker->sentence(2, false),
            'location' => $this->faker->city(),
            'remote_working' => $this->faker->boolean(),
            'salary' => $this->faker->randomNumber(5),
            'working_day' => $this->faker->randomElement(['full-time', 'part-time']),
            'company' => $this->faker->company,
            'logo' => null,
            'is_approved' => true
        ];
    }
}
