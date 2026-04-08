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
        User::factory()->create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
        ]);

        $bot = Bot::create([
            'name' => 'ParallelResellers',
            'discord_channel_id' => '123456789012345678'
        ]);


        ScrapedData::factory()->count(10000)->create([
            'bot_id' => $bot->id
        ]);
    }
}