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
            foreach ($data as $item) {
                DB::table('scraped_data')->insert([
                    'bot_id'     => $dbBot->id, 
                    'author'     => $item['user'],
                    'content'    => $item['text'],
                    'item_name'  => $item['item'],
                    'scraped_at' => Carbon::parse($item['time']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Scrape history
            DB::table('scrape_history')->insert([
                'bot_id'        => $dbBot->id,
                'records_found' => count($data),
                'status'        => 'success',
                'created_at'    => now(),
            ]);

            return view('page4', [
                'purchases' => $data,
                'botName'   => $botName,
                'channelId' => $channelId,
                'totalSum'  => 0 
            ]);
        }

        return back()->with('error', 'Error: ' . $result->errorOutput());
    }
}