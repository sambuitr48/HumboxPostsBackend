<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
    return [
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'location' => $this->faker->city,
        'user_id' => \App\Models\User::factory(),
    ];
    }
}
