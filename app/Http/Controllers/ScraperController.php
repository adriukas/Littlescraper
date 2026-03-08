<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScraperController extends Controller
{
    public function runScraper(Request $request) {
        $channelId = $request->input('channel_id');
        $botName = $request->input('bot_name'); 
        
        $token = env('DISCORD_TOKEN');
        $scriptPath = base_path('script/littlescraper.py');

        $result = Process::run("python3 {$scriptPath} {$token} {$channelId}");

        if ($result->successful()) {
            $data = json_decode($result->output(), true);

            if (empty($data)) {
                return back()->with('error', 'No data found.');
            }

            // Bots
            DB::table('bots')->updateOrInsert(
                ['discord_channel_id' => $channelId],
                ['name' => $botName, 'updated_at' => now()]
            );
            
            $dbBot = DB::table('bots')->where('discord_channel_id', $channelId)->first();

            // Messages
            foreach ($data as &$item) {
                if (preg_match('/Price:\s*([\d\.]+)/i', $item['text'], $matches)) {
                    $item['price'] = (float)$matches[1];
                } else {
                    $item['price'] = 0; 
                }

                DB::table('scraped_data')->insert([
                    'bot_id'     => $dbBot->id, 
                    'author'     => $item['user'],
                    'content'    => $item['text'], 
                    'price'      => $item['price'], 
                    'scraped_at' => \Carbon\Carbon::parse($item['time']),
                    'created_at' => now(),
                ]);
            }

            // Scrape history
            DB::table('scrape_history')->insert([
                'bot_id'        => $dbBot->id,
                'records_found' => count($data),
                'status'        => 'success',
                'created_at'    => now(),
            ]);


            $totalSum = array_sum(array_column($data, 'price'));

            return view('page4', [
                'purchases' => $data,
                'botName' => $botName,
                'channelId' => $channelId,
                'totalSum' => $totalSum // Perduodame sumą į puslapį
            ]);
                    }

        return back()->with('error', 'Error: ' . $result->errorOutput());
    }
}