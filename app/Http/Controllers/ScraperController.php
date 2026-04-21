<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScraperService;
use App\Models\ScrapedData;

class ScraperController extends Controller
{
    protected $scraperService;
    // adding scraperService to the constructor 
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

    // 1. Jei tai GET užklausa, TIK parodome tuščią puslapį (be lentelės)
    if ($request->isMethod('get')) {
        return view($viewName, [
            'botName' => $botName,
            'channelId' => $channelId,
            'purchases' => null, // Svarbu: perduodame null, kad Blade nerodytų lentelės
            'totalSum' => 0
        ]);
    }

    // 2. Jei tai POST užklausa (paspaudei mygtuką), Vykdome skrepinimą
    $request->validate([
        'channel_id' => 'required|numeric',
        'bot_name'   => 'required|string',
    ]);

    $data = $this->scraperService->scrape($botName, $botType, $channelId);

    if ($request->has('debug')) {
        dd($data);
    }

    $yesterday = now()->subDay();
    $filteredData = collect($data)->filter(function ($item) use ($yesterday) {
        return \Carbon\Carbon::parse($item['time'])->gte($yesterday);
    })->values()->all();

    $totalSum = array_sum(array_column($filteredData, 'price'));

    return view($viewName, [
        'purchases' => $filteredData, 
        'channelId' => $channelId,
        'botName'   => $botName,
        'totalSum'  => $totalSum ?? 0
    ]);
}
    public function showHistory(Request $request)
    {
        $query = ScrapedData::with('bot');

        if ($request->is('history/messages')) {
            $query->where('price', 0);
            $view = 'history_messages';
        } else {
            $query->where('price', '>', 0);
            $view = 'history_sales';
        }

        if ($request->has('bot')) {
            $botName = $request->query('bot');
            $query->whereHas('bot', function($q) use ($botName) {
                $q->where('name', $botName);
            });
        }

        $purchases = $query->orderBy('scraped_at', 'desc')->paginate(15);
        
        $totalSum = ($view === 'history_sales') ? $query->sum('price') : 0;

        return view($view, compact('purchases', 'totalSum'));
    }
    public function destroy($id)
        {
        $data = ScrapedData::find($id);

        if (!$data) {
            return back()->with('error', 'Įrašas nerastas.');
        }

        $data->delete();

        \Illuminate\Support\Facades\Log::info("[ScraperController@destroy] Admin deleted record ID: " . $id);

        return back()->with('success', 'Record deleted successfully.');
}
}