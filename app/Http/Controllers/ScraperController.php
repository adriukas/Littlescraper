<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Process;
use Illuminate\Http\Request;

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
            return back()->with('error',  'No data was found in this channel.');
        }

        return view('page4', [
            'purchases' => $data,
            'botName' => $botName,
            'channelId' => $channelId,
        ]);
        

    }
    return back()->with('error', 'Scraper crashed: ' . $result->errorOutput());
    }
}