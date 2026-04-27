<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Bot;

class BotController extends Controller
{
    public function dashboard()
    {
        $bots = Bot::getAllBots();
        return view('page3', compact('bots'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only admins can add bots.');
        }

        $request->validate([
            'name'               => 'required|string|max:50',
            'discord_channel_id' => 'required|numeric',
            'token'              => 'required|string|min:10',
            'type'               => 'required|in:SALES,MESSAGE',
        ]);

        $bot = Bot::updateOrCreate(
            ['discord_channel_id' => $request->discord_channel_id],
            [
                'name'  => $request->name,
                'token' => $request->token,
                'type'  => $request->type,
            ]
        );

        Log::info("[BotController@store] Bot added/updated: {$bot->name} by user: " . Auth::user()->email);
        return back()->with('success', 'Bot successfully updated or added!');
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only admins can edit bots.');
        }

        $request->validate([
            'name'               => 'required|string|max:50',
            'discord_channel_id' => 'required|numeric',
            'token'              => 'required|string|min:10',
        ]);

        $bot = Bot::getBotById($id);
        $oldName = $bot->name;

        $bot->updateBot([
            'name'               => $request->name,
            'discord_channel_id' => $request->discord_channel_id,
            'token'              => $request->token,
        ]);

        Log::info("[BotController@update] Bot updated. Old: {$oldName}, New: {$bot->name} by user: " . Auth::user()->email);
        return back()->with('success', 'Bot details updated successfully!');
    }

    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only admins can delete bots.');
        }

        $bot = Bot::getBotById($id);
        Log::info("[BotController@destroy] Bot deleted: {$bot->name} by user: " . Auth::user()->email);
        $bot->deleteBot();

        return back()->with('success', 'Bot deleted successfully.');
    }
}
