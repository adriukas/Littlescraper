<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;
use App\Models\Bot;
use App\Models\ScrapedData;
use App\Models\ScrapeHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScraperService
{
    public function scrape($botName, $botType, $channelId)
    {
        $data = $this->runPythonScraper($channelId);

        if (!$data) {
            return null;
        }

        $bot = $this->storeBot($botName, $channelId);

        $processedData = $this->storeScrapedMessages($data, $bot->id);

        $this->storeHistory($bot->id, count($processedData));

        return $processedData;
    }

    private function runPythonScraper($channelId)
    {
        $token = env('DISCORD_TOKEN');
        $scriptPath = base_path('script/littlescraper.py');

        $result = Process::run("python3 {$scriptPath} \"{$token}\" \"{$channelId}\"");
        
        if (!$result->successful()) {
            Log::error("[ScraperService@runPythonScraper] Python process failed. Error: " . $result->errorOutput());
            return null;
        }

        Log::info("[ScraperService@runPythonScraper] Successfully executed for channel: " . $channelId);
        return json_decode($result->output(), true);
    }

    private function storeBot($botName, $channelId)
    {
        return Bot::updateOrCreate(
            ['discord_channel_id' => $channelId],
            ['name' => $botName]
        );
    }

private function storeScrapedMessages($data, $botId)
{
    foreach ($data as &$item) { 
        $scrapedAt = Carbon::parse($item['time']);

        // Ištraukiame kainą iš teksto
        if (preg_match('/Price:\s*([\d\.]+)/i', $item['text'], $matches)) {
            $item['price'] = (float)$matches[1];
        } else {
            $item['price'] = 0;
        }

        $exists = ScrapedData::where('bot_id', $botId)
            ->where('author', $item['user'])
            ->where('content', $item['text'])
            ->where('scraped_at', $scrapedAt)
            ->exists();

        if (!$exists) {
            ScrapedData::create([
                'bot_id'     => $botId,
                'author'     => $item['user'],
                'content'    => $item['text'],
                'price'      => $item['price'],
                'scraped_at' => $scrapedAt,
            ]);
        }
    }

    return $data; 
}

    private function storeHistory($botId, $count)
    {
        ScrapeHistory::create([
            'bot_id' => $botId,
            'records_found' => $count,
            'status' => 'success'
        ]);
    }
}