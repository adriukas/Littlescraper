<?php

namespace Database\Factories;

use App\Models\Bot;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScrapedDataFactory extends Factory
{
    public function definition(): array
    {
        return [
            'bot_id'     => Bot::inRandomOrder()->value('id') ?? 1,
            'author'     => $this->faker->userName(),
            'content'    => $this->faker->sentence(12),
            'item_name'  => $this->faker->words(3, true),
            'price'      => $this->faker->randomFloat(2, 5, 500),
            'scraped_at' => now()->subMinutes(rand(1, 40000)),
        ];
    }
}
