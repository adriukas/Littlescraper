<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScraperService;
use App\Models\ScrapedData;
use App\Models\Bot;
use Illuminate\Support\Facades\Log;

class ScraperController extends Controller
{
    protected $scraperService;

    public function __construct(ScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }

public function loginCheck(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);
    
    $user = \App\Models\User::where('email', $request->email)->first();
    
    if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        \Illuminate\Support\Facades\Auth::login($user); 
        session(['is_logged_in' => true, 'user_email' => $request->email]);
        return redirect()->route('home');
    }

    return back()
        ->withErrors(['login' => 'Incorrect email or password.']) 
        ->withInput($request->only('email'));
}

    public function logout()
    {
        \Illuminate\Support\Facades\Auth::logout();
        session()->forget(['is_logged_in', 'user_email']);
        Log::info("[Auth] User logged out.");
        return redirect()->route('info');
    }

    public function dashboard()
    {
        if (!session('is_logged_in')) return redirect()->route('login');
        $bots = \App\Models\Bot::all(); 
        return view('page3', compact('bots'));
    }

    public function storeBot(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'discord_channel_id' => 'required|numeric',
            'token' => 'required|string',
            'type' => 'required|in:SALES,MESSAGE'
        ]);

        \App\Models\Bot::updateOrCreate(
            ['discord_channel_id' => $request->discord_channel_id], 
            [
                'name' => $request->name,
                'token' => $request->token,
                'type' => $request->type
            ]
        );

        Log::info("[Admin] Bot added/updated: " . $request->name);
        return back()->with('success', 'Bot successfully updated or added!');
    }
    /**
     * Skrepinimo vykdymas
     */
    public function runScraper(Request $request)
    {
        $botName   = $request->input('bot_name');
        $channelId = $request->input('channel_id');
        $botType   = $request->input('type'); 

        $viewName = ($botType === 'MESSAGE') ? 'messages' : 'sales';

        if ($request->isMethod('get')) {
            return view($viewName, [
                'botName' => $botName,
                'channelId' => $channelId,
                'purchases' => null, 
                'totalSum' => 0
            ]);
        }

        $request->validate([
            'channel_id' => 'required|numeric',
            'bot_name'   => 'required|string',
        ]);

        // LOG 1: Skrepinimo pradžia
        Log::info("[Scraper] User started scraping for bot: {$botName} (ID: {$channelId})");

        $data = $this->scraperService->scrape($botName, $botType, $channelId);

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

    /**
     * Istorijos atvaizdavimas (Šios funkcijos trūko tavo klaidos pranešime)
     */
    public function showHistory(Request $request)
    {
        $query = ScrapedData::with('bot');

        // Atskiriame tipus pagal URL kelią
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

        $totalSum = $query->sum('price');
        $purchases = $query->orderBy('scraped_at', 'desc')->paginate(15);

        return view($view, compact('purchases', 'totalSum'));
    }


    /**
     * Duomenų įrašo trynimas
     */
    public function destroy($id)
    {
        $data = ScrapedData::find($id);

        if (!$data) {
            return back()->with('error', 'Įrašas nerastas.');
        }

        $data->delete();

        // LOG 3: Įrašo ištrynimas
        Log::info("[ScraperController@destroy] Admin deleted record ID: " . $id);

        return back()->with('success', 'Record deleted successfully.');
    }

    /**
     * Istorijos duomenų redagavimas
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'author'  => 'required|string|max:255',
            'content' => 'required|string',
            'price'   => 'nullable|numeric',
            'item_name' => 'nullable|string|max:255',
        ]);

        $item = ScrapedData::findOrFail($id);
        $item->update([
            'author'    => $request->author,
            'price'     => $request->price ?? $item->price,
            'content'   => $request->content,
            'item_name' => $request->item_name ?? $item->item_name,
        ]);

        // LOG 4: Duomenų redagavimas
        Log::info("[DataUpdate] Record ID {$id} was updated by admin.");

        return back()->with('success', 'Record updated successfully!');
    }

    /**
     * Paties boto nustatymų redagavimas
     */
    public function updateBot(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'discord_channel_id' => 'required|numeric',
            'token' => 'required|string', // PRIDĖTA: privalome patikrinti tokeną
        ]);

        $bot = Bot::findOrFail($id);
        $oldName = $bot->name;
        
        $bot->update([
            'name' => $request->name,
            'discord_channel_id' => $request->discord_channel_id,
            'token' => $request->token, // Dabar tokenas bus saugiai atnaujintas
        ]);

        Log::info("[Admin] Bot updated. Old name: {$oldName}, New name: {$bot->name}");

        // Naudojame .withInput() klaidos atveju (Laravel validate() tai daro automatiškai), 
        // bet sėkmės atveju tiesiog grįžtame:
        return redirect()->back()->with('success', 'Bot details updated successfully!');
    }
}