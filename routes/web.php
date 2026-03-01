<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScraperController;


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

Route::get('/page4', function () {
    return view('page4');
})->name('scraper');

Route::post('/run-scrape', [ScraperController::class, 'runScraper']);
Route::get('/test-discord', [App\Http\Controllers\testcontrol::class, 'checkDiscord']);