<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'property_id' => \App\Models\Property::factory(),
            'content' => $this->faker->text(200),
            'deleted' => $this->faker->boolean(10), // 10% chance of being deleted
        ];
    }
}
