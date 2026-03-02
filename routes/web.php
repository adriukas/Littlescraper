<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScraperController;
use Illuminate\Http\Request;


// web.php
Route::get('/', function () {
    return view('page1');
})->name('info');

Route::get('/page2', function () {
    return view('page2');
})->name('login');

Route::get('/page3', function () {
    return view('page3');
})->name('home');

Route::post('/run-scrape', [ScraperController::class, 'runScraper']);

Route::get('/page4', function (Request $request) {
    $botName = $request->query('bot', 'Unknown Bot');
    $botType = $request->query('type');
    
    // Look up the ID from .env based on the 'type'
    $channelId = env("ID_" . $botType);

    return view('page4', [
        'botName' => $botName,
        'channelId' => $channelId
    ]);
})->name('scraper');