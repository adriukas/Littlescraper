<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScraperService;
use App\Models\ScrapedData;

class ScraperController extends Controller
{
    protected $scraperService;

    public function __construct(ScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }

    public function runScraper(Request $request)
    {
        // 1. Pasiimame parametrus
        $botName = $request->input('bot_name') ?? $request->query('bot');
        $botType = $request->input('type') ?? $request->query('type');
        $channelId = $request->input('channel_id') ?? env("ID_" . $botType);

        // 2. Automatiškai nustatome vaizdo (view) pavadinimą pagal bota
        // Pridedame visus botus iš tavo nuotraukos į atitinkamas grupes
        $messageBots = ['ASTRAL', 'FLIPFLOW', 'PARALLEL', 'ARCHIEV', 'DOTB']; 
        
        // Tikriname, ar botType yra žinučių grupėje
        $viewName = in_array($botType, $messageBots) ? 'messages' : 'sales';

        // 3. JEI TAI GET UŽKLAUSA (tik užeiname į bota iš Page 3)
        if ($request->isMethod('get')) {
            return view($viewName, [
                'botName' => $botName,
                'channelId' => $channelId,
                'purchases' => null // Lentelė nebus rodoma
            ]);
        }

        // 4. JEI TAI POST UŽKLAUSA (paspaudėme "Scrape" mygtuką)
        $data = $this->scraperService->scrape($botName, $botType, $channelId);

        // Apsauga, jei skreperis nieko nerado arba Python sugedo
        if ($data === null) {
            return back()->with('error', 'Scraper failed to connect to Discord.');
        }

        $totalSum = array_sum(array_column($data, 'price'));

        // Grąžiname tą patį vaizdą su gautais duomenimis
        return view($viewName, [
            'purchases' => $data, // Čia perduodame sugrąžintus duomenis
            'data' => $data,      // Kai kurios tavo Blade versijos naudoja $data
            'botName' => $botName,
            'channelId' => $channelId,
            'totalSum' => $totalSum
        ]);
    }

    public function showHistory(Request $request)
    {
        $isChat = $request->is('history_messages');
        $query = ScrapedData::with('bot');

        if ($isChat) {
            // 1. Patikriname ar vartotojas pasirinko konkretų botą
            $selectedBot = $request->query('bot');

            $query->whereHas('bot', function($q) use ($selectedBot) {
                if ($selectedBot) {
                    // Jei pasirinktas botas, filtruojame tik jį
                    $q->where('name', $selectedBot);
                } else {
                    // Jei nepasirinkta, rodome abu
                    $q->whereIn('name', ['Astral', 'FlipFlow']);
                }
            });
            $view = 'history_messages';
            $totalSum = 0;
        } else {
            // Tavo esama Sales logika...
            $query->whereHas('bot', function($q) {
                $q->whereIn('name', ['ParallelResellers', 'VintedSeekers', 'BartoResell']);
            });
            $view = 'history_sales';
            $totalSum = (clone $query)->sum('price'); 
        }

        $purchases = $query->orderBy('scraped_at', 'desc')->paginate(15);

        return view($view, compact('purchases', 'totalSum'));
    }
}