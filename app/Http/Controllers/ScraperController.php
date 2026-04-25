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

    // Jei tai GET užklausa, TIK parodome tuščią puslapį (be lentelės)
    if ($request->isMethod('get')) {
        return view($viewName, [
            'botName' => $botName,
            'channelId' => $channelId,
            'purchases' => null, 
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
        $query = \App\Models\ScrapedData::with('bot');

        // Atskiriame Sales nuo Messages
        if ($request->is('history/messages')) {
            $query->whereHas('bot', function($q) {
                $q->where('type', 'MESSAGE');
            });
            $view = 'history_messages';
        } else {
            $query->whereHas('bot', function($q) {
                $q->where('type', 'SALES');
            });
            $view = 'history_sales';
        }

        // Filtravimas pagal boto vardą
        if ($request->has('bot')) {
            $botName = $request->query('bot');
            $query->whereHas('bot', function($q) use ($botName) {
                $q->where('name', $botName);
            });
        }

        // SVARBU: Sumą skaičiuojame PRIEŠ puslapiavimą
        $totalSum = $query->sum('price');

        // Gauname duomenis su puslapiavimu
        $purchases = $query->orderBy('scraped_at', 'desc')->paginate(15);

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
    public function update(Request $request, $id)
    {
        // Neleidžiame išsaugoti tuščio autoriaus ar turinio
        $request->validate([
            'author'  => 'required|string|max:255',
            'content' => 'required|string',
            'price'   => 'nullable|numeric',
            'item_name' => 'nullable|string|max:255',
        ]);

        $item = \App\Models\ScrapedData::findOrFail($id);

        // Jei validacija praėjo, duomenys bus saugūs
        $item->update([
            'author'    => $request->author,
            'price'     => $request->price ?? $item->price,
            'content'   => $request->content,
            'item_name' => $request->item_name ?? $item->item_name,
        ]);

        return back()->with('success', 'Record updated successfully!');
    }
    public function updateBot(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:50',
        'discord_channel_id' => 'required|numeric', // Tikrina duomenų tipą (pattern)
    ]);

    $bot = \App\Models\Bot::findOrFail($id);
    
    $bot->update([
        'name' => $request->name,
        'discord_channel_id' => $request->discord_channel_id,
    ]);

    return redirect()->back()->with('success', 'Bot details updated successfully!');
}
}