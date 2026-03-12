<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;
use App\Models\Bot;
use App\Models\ScrapedData;
use App\Models\ScrapeHistory;
use Carbon\Carbon;

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

        $result = Process::run("python3 {$scriptPath} {$token} {$channelId}");

        if (!$result->successful()) {
            return null;
        }

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
    foreach ($data as &$item) { // Naudojame &, kad galėtume papildyti $item masyvą kaina
        $scrapedAt = Carbon::parse($item['time']);

        // 1. Ištraukiame kainą iš teksto
        if (preg_match('/Price:\s*([\d\.]+)/i', $item['text'], $matches)) {
            $item['price'] = (float)$matches[1];
        } else {
            $item['price'] = 0;
        }

        // 2. Patikriname, ar toks įrašas jau egzistuoja
        $exists = ScrapedData::where('bot_id', $botId)
            ->where('author', $item['user'])
            ->where('content', $item['text'])
            ->where('scraped_at', $scrapedAt)
            ->exists();

        // 3. Įrašome į bazę TIK JEI tai nauja žinutė
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

    return $data; // Grąžiname VISUS duomenis (senus + naujus) rodymui lentelėje
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