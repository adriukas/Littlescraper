<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScraperService;
use App\Models\ScrapedData;
use App\Models\Bot;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ScraperController extends Controller
{
    protected $scraperService;

    public function __construct(ScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }

    public function runScraper(Request $request)
    {
        $botName   = $request->input('bot_name');
        $channelId = $request->input('channel_id');
        $botType   = $request->input('type');

        $viewName = ($botType === 'MESSAGE') ? 'messages' : 'sales';

        if ($request->isMethod('get')) {
            return view($viewName, [
                'botName'   => $botName,
                'channelId' => $channelId,
                'purchases' => null,
                'totalSum'  => 0
            ]);
        }

        $request->validate([
            'channel_id' => 'required|numeric',
            'bot_name'   => 'required|string',
        ]);

        Log::info("[ScraperController@runScraper] Scrape started for bot: {$botName} (channel: {$channelId})");

        $data = $this->scraperService->scrape($botName, $botType, $channelId);

        $yesterday = now()->subDay();
        $filteredData = collect($data)->filter(function ($item) use ($yesterday) {
            return Carbon::parse($item['time'])->gte($yesterday);
        })->values()->all();

        $totalSum = array_sum(array_column($filteredData, 'price'));

        return view($viewName, [
            'purchases' => $filteredData,
            'channelId' => $channelId,
            'botName'   => $botName,
            'totalSum'  => $totalSum
        ]);
    }

    public function showHistory(Request $request)
    {
        $query = ScrapedData::with('bot');

        if ($request->is('history/messages')) {
            $query->whereHas('bot', fn($q) => $q->where('type', 'MESSAGE'));
            $view = 'history_messages';
            $bots = Bot::where('type', 'MESSAGE')->get();
        } else {
            $query->whereHas('bot', fn($q) => $q->where('type', 'SALES'));
            $view = 'history_sales';
            $bots = Bot::where('type', 'SALES')->get();
        }

        if ($request->has('bot')) {
            $botName = $request->query('bot');
            $query->whereHas('bot', fn($q) => $q->where('name', $botName));
        }

        $totalSum  = $query->sum('price');
        $purchases = $query->orderBy('scraped_at', 'desc')->paginate(15);

        return view($view, compact('purchases', 'totalSum', 'bots'));
    }

    public function destroy($id)
    {
        $data = ScrapedData::find($id);

        if (!$data) {
            return back()->with('error', 'Record not found.');
        }

        $data->delete();
        Log::info("[ScraperController@destroy] Admin deleted record ID: " . $id);

        return back()->with('success', 'Record deleted successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'author'    => 'required|string|max:255',
            'content'   => 'required|string',
            'price'     => 'nullable|numeric',
            'item_name' => 'nullable|string|max:255',
        ]);

        $item = ScrapedData::findOrFail($id);
        $item->update([
            'author'    => $request->author,
            'content'   => $request->content,
            'price'     => $request->price ?? $item->price,
            'item_name' => $request->item_name ?? $item->item_name,
        ]);

        Log::info("[ScraperController@update] Record ID {$id} updated by admin.");

        return back()->with('success', 'Record updated successfully!');
    }
}
