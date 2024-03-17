<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(rand(1, 4), true),
            'sku' => fake()->unique()->lexify('???-???'),
            'date_published' => fake()->date('Y-m-d', 'now'),
            'description' => fake()->text(100),
            'price' => fake()->randomFloat(2, 10, 200),
            'author' => fake()->name(),
        ];
    }
}
