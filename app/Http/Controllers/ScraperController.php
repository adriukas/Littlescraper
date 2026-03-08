<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScraperController extends Controller
{
    public function runScraper(Request $request) {
        $botName = $request->input('bot_name') ?? $request->query('bot');
        $botType = $request->input('type') ?? $request->query('type');
        
        $channelId = $request->input('channel_id') ?? env("ID_" . $botType);
        
        $token = env('DISCORD_TOKEN');
        $scriptPath = base_path('script/littlescraper.py');

        $result = Process::run("python3 {$scriptPath} {$token} {$channelId}");

        if ($result->successful()) {
            $data = json_decode($result->output(), true);

            if (empty($data)) {
                return back()->with('error', 'No data found.');
            }

            DB::table('bots')->updateOrInsert(
                ['discord_channel_id' => $channelId],
                ['name' => $botName, 'updated_at' => now()]
            );
            
            $dbBot = DB::table('bots')->where('discord_channel_id', $channelId)->first();

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
                    'scraped_at' => Carbon::parse($item['time']),
                    'created_at' => now(),
                ]);
            }

            DB::table('scrape_history')->insert([
                'bot_id'        => $dbBot->id,
                'records_found' => count($data),
                'status'        => 'success',
                'created_at'    => now(),
            ]);

            $totalSum = array_sum(array_column($data, 'price'));

        
            if ($botType === 'ASTRAL' || $botType === 'FLIPFLOW') {
                return view('messages', [
                    'purchases' => $data,
                    'botName' => $botName,
                    'channelId' => $channelId
                ]);
            }

            return view('page4', [
                'purchases' => $data,
                'botName' => $botName,
                'channelId' => $channelId,
                'totalSum' => $totalSum 
            ]);
        }

        return back()->with('error', 'Error: ' . $result->errorOutput());
    }

public function showHistory() {
    $query = DB::table('scraped_data')
        ->join('bots', 'scraped_data.bot_id', '=', 'bots.id')
        ->select('scraped_data.*', 'bots.name as bot_name');

    if (request()->is('history_messages')) {
        $purchases = $query->whereIn('bots.name', ['Astral', 'FlipFlow'])
                           ->orderBy('scraped_at', 'desc')
                           ->paginate(15);
        
        return view('history_messages', [
            'purchases' => $purchases,
            'totalSum' => 0 
        ]);
    }

    $purchases = $query->whereIn('bots.name', ['ParallelResellers', 'VintedSeekers', 'BartoResell'])
                       ->orderBy('scraped_at', 'desc')
                       ->paginate(15);

    $totalSum = $query->whereIn('bots.name', ['ParallelResellers', 'VintedSeekers', 'BartoResell'])
                      ->sum('price');

    return view('history_sales', [
        'purchases' => $purchases,
        'totalSum' => $totalSum
    ]);
    }
}