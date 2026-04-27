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

        $bot = $this->syncBot($botName, $channelId);

        $processedData = $this->storeScrapedMessages($data, $bot->id);

        $bot->update(['last_scraped_at' => now()]);

        $this->storeHistory($bot->id, count($processedData));

        return $processedData;
    }

    private function runPythonScraper($channelId)
    {
        $bot = Bot::where('discord_channel_id', $channelId)->first();
        $token = ($bot && $bot->token) ? $bot->token : env('DISCORD_TOKEN');
        $scriptPath = base_path('script/littlescraper.py');

        $result = Process::run("python3 {$scriptPath} \"{$token}\" \"{$channelId}\"");

        if (!$result->successful()) {
            Log::error("[ScraperService@runPythonScraper] Python process failed: " . $result->errorOutput());
            return null;
        }

        Log::info("[ScraperService@runPythonScraper] Success for channel: " . $channelId);
        return json_decode($result->output(), true);
    }

    private function syncBot($botName, $channelId)
    {
        return Bot::updateOrCreate(
            ['discord_channel_id' => $channelId],
            ['name' => $botName]
        );
    }

    private function storeScrapedMessages($data, $botId)
    {
        foreach ($data as $item) {
            $scrapedAt = Carbon::parse($item['time']);

            if (preg_match('/Price:\s*([\d\.]+)/i', $item['text'], $matches)) {
                $item['price'] = (float) $matches[1];
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
                    'item_name'  => $item['item'] ?? null,
                ]);
            }
        }

        return $data;
    }

    private function storeHistory($botId, $count, $executionTime = 0, $error = null)
    {
        ScrapeHistory::createRecord([
            'bot_id'         => $botId,
            'records_found'  => $count,
            'status'         => $error ? 'failed' : 'success',
            'execution_time' => $executionTime,
            'error_log'      => $error,
            'request_ip'     => request()->ip(),
        ]);
    }
}
