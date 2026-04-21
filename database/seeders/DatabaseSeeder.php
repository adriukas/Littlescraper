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
        User::updateOrCreate(
            ['email' => env('USER2_EMAIL', 'user@example.com')],
            [
                'name' => 'Regular User',
                'password' => Hash::make(env('USER2_PASSWORD', 'password')),
            ]
        
        );
            
            $bots = [
                [
                    'name' => 'ASTRAL',
                    'discord_channel_id' => env('ID_ASTRAL'),
                    'token' => env('DISCORD_TOKEN'),
                ],
                [
                    'name' => 'FLIPFLOW',
                    'discord_channel_id' => env('ID_FLIPFLOW'),
                    'token' => env('DISCORD_TOKEN'),
                ],
                [
                    'name' => 'PARALLEL',
                    'discord_channel_id' => env('ID_PARALLEL'),
                    'token' => env('DISCORD_TOKEN'),
                ],
            ];


            foreach ($bots as $botData) {
                // Naudojame updateOrCreate, kad netyčia nesidubliuotų
                Bot::updateOrCreate(
                    ['discord_channel_id' => $botData['discord_channel_id']],
                    ['name' => $botData['name'], 'token' => $botData['token']]
                );
            }
        }
                

        /* 2. create bots mentioned in my project
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
        }*/
};
