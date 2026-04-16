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

        $botName = $request->input('bot_name') 
                ?? $request->query('bot') 
                ?? session('last_bot_name');

        $botType = $request->input('type') 
                ?? $request->query('type') 
                ?? session('last_bot_type');

        if (!$botName) {
            return redirect()->route('home')->with('error', 'Please select a bot first.');
        }

        session(['last_bot_name' => $botName, 'last_bot_type' => $botType]);


        $channelId = $request->input('channel_id') ?? env("ID_" . $botType);

        $messageBots = ['ASTRAL', 'FLIPFLOW', 'PARALLEL', 'ARCHIEV', 'DOTB']; 
        $viewName = in_array($botType, $messageBots) ? 'messages' : 'sales';

        if ($request->isMethod('get')) {
            return view($viewName, [
                'botName' => $botName,
                'channelId' => $channelId,
                'purchases' => null 
            ]);
        }

        $request->validate([
            'channel_id' => 'required|numeric|digits_between:15,25',
            'bot_name'   => 'required|string|min:3|max:50',
            'type'       => 'required|alpha|uppercase',
        ], [
            'channel_id.numeric' => 'Discord ID has to be numbers.',
            'bot_name.min' => 'Bot name is too short.'
        ]);

        $data = $this->scraperService->scrape($botName, $botType, $channelId);

        if ($data === null) {
            return back()->with('error', 'Scraper failed to connect to Discord.');
        }

        $yesterday = now()->subDay();

        $filteredData = collect($data)->filter(function ($item) use ($yesterday) {
            return \Carbon\Carbon::parse($item['time'])->gte($yesterday);
        })->values()->all(); // Reset array keys and convert back to array

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