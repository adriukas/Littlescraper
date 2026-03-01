<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Process;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    public function runScraper()
    {
        $scriptPath = base_path('script/littlescraper.py');
        
        // from the env file, get the token and channel ID
        $token = env('DISCORD_TOKEN');
        $channelId = env('DISCORD_CHANNEL_ID_1');

        // passing all the arguments to the script
        $result = Process::run("python3 {$scriptPath} {$token} {$channelId}");

        if ($result->successful()) {
            // get the raw text (JSON) coming back from the Python script
            $rawText = $result->output();
             // convert that text into a PHP list (an array) so Laravel can read it
            $purchases = json_decode($rawText, true);
            // send that list to 'page4.blade.php' so it can be shown in your table
            return view('page4', ['purchases' => $purchases]);
            }
        // if the script failed, return back with an error message
        return back()->with('error', 'Scraper failed to run: ' . $result->errorOutput());
    }
}