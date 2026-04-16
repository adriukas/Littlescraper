<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Bot;
use App\Models\ScrapedData;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. create or update
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => 'Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ]
        );

        // 2. create bots mentioned in my project
        $botNames = ['ParallelResellers', 'Astral', 'FlipFlow', 'Archiev', 'DotB'];
        $bots = [];

        foreach ($botNames as $name) {
            $bots[] = Bot::updateOrCreate(
                ['name' => $name],
                ['discord_channel_id' => fake()->numerify('##################')]
            );
        }

        // 3. creating 2000 fro every bot
        foreach ($bots as $bot) {
            ScrapedData::factory()->count(2000)->create([
                'bot_id' => $bot->id
            ]);
        }
    }
}