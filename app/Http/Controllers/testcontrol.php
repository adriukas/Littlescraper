<?php
# just to test if the python script is working and can be called from Laravel, 
# this will be removed later

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Process;
use Illuminate\Http\Request;

class testcontrol extends Controller
{
    public function checkDiscord()
{
    $scriptPath = base_path('script/test.py');
    
    $token = env('DISCORD_TOKEN');    
    $channelId = env('DISCORD_CHANNEL_ID_1');

    $result = Process::run("python3 {$scriptPath} {$token} {$channelId}");

    if ($result->successful()) {
        $output = json_decode($result->output(), true);
        dd($output);
    }

    dd([
        'Status' => 'Failed to run script',
        'Error' => $result->errorOutput()
    ]);
}
}