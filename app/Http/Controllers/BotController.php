<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bot;
use Illuminate\Support\Facades\Log;

class BotController extends Controller
{
    public function dashboard()
    {
        $bots = Bot::all();
        return view('page3', compact('bots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'discord_channel_id' => 'required|numeric',
            'token' => 'required|string',
            'type' => 'required|in:SALES,MESSAGE'
        ]);

        Bot::updateOrCreate(
            ['discord_channel_id' => $request->discord_channel_id],
            [
                'name' => $request->name,
                'token' => $request->token,
                'type' => $request->type
            ]
        );

        Log::info("[BotController@store] Bot added/updated: " . $request->name);
        return back()->with('success', 'Bot successfully updated or added!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'discord_channel_id' => 'required|numeric',
            'token' => 'required|string',
        ]);

        $bot = Bot::findOrFail($id);
        $oldName = $bot->name;

        $bot->update([
            'name' => $request->name,
            'discord_channel_id' => $request->discord_channel_id,
            'token' => $request->token,
        ]);

        Log::info("[BotController@update] Bot updated. Old name: {$oldName}, New name: {$bot->name}");
        return back()->with('success', 'Bot details updated successfully!');
    }

    public function destroy($id)
    {
        $bot = Bot::findOrFail($id);
        Log::info("[BotController@destroy] Bot deleted: " . $bot->name);
        $bot->delete();
        return back()->with('success', 'Bot deleted successfully.');
    }
}
