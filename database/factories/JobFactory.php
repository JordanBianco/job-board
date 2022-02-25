<?php

namespace Database\Factories;

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
        $title = $this->faker->sentence(2);
        $slug = Str::slug($title);

        return [
            'user_id' => User::all()->random()->id,
            'company' => $this->faker->company,
            'title' => $title,
            'slug' => $slug,
            'position' => $this->faker->sentence(2, false),
            'description' => $this->faker->paragraph(rand(20, 30)),
            'logo' => null,
            'location' => $this->faker->city(),
            'min_salary' => $this->faker->randomNumber(5),
            'max_salary' => $this->faker->randomNumber(5),
            'is_approved' => true
        ];
    }
}
