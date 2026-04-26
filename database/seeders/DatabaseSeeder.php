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
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name'     => 'Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ]
        );

        User::updateOrCreate(
            ['email' => env('USER2_EMAIL', 'user@example.com')],
            [
                'name'     => 'Regular User',
                'password' => Hash::make(env('USER2_PASSWORD', 'password')),
            ]
        );

        $bots = [
            [
                'name'               => 'ASTRAL',
                'discord_channel_id' => env('ID_ASTRAL'),
                'token'              => env('DISCORD_TOKEN'),
                'type'               => 'SALES',
            ],
            [
                'name'               => 'FLIPFLOW',
                'discord_channel_id' => env('ID_FLIPFLOW'),
                'token'              => env('DISCORD_TOKEN'),
                'type'               => 'SALES',
            ],
            [
                'name'               => 'PARALLEL',
                'discord_channel_id' => env('ID_PARALLEL_neveikia'),
                'token'              => env('DISCORD_TOKEN'),
                'type'               => 'SALES',
            ],
        ];

        foreach ($bots as $botData) {
            Bot::updateOrCreate(
                ['discord_channel_id' => $botData['discord_channel_id']],
                [
                    'name'  => $botData['name'],
                    'token' => $botData['token'],
                    'type'  => $botData['type'],
                ]
            );
        }

        // Seed 10000 scraped_data records for the first SALES bot
        $bot = Bot::where('type', 'SALES')->first();
        if ($bot && ScrapedData::count() < 10000) {
            ScrapedData::factory()->count(10000)->create(['bot_id' => $bot->id]);
        }
    }
}
