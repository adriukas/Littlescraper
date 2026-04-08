<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScrapedData>
 */
class ScrapedDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
{
    return [
        'bot_id' => 1, 
        'author' => $this->faker->userName(),
        'content' => $this->faker->sentence(12), // Sugeneruoja netikrą žinutę
        'price' => $this->faker->randomFloat(2, 5, 500), // Kaina nuo 5 iki 500 €
        'scraped_at' => now()->subMinutes(rand(1, 40000)), // Atsitiktinis laikas per pastarąjį mėnesį
    ];
}
}
