<?php

use App\Http\Controllers\ScraperController;
use Illuminate\Support\Facades\Route;

// Vieši maršrutai
Route::get('/', function () { return view('page1'); })->name('info');
Route::get('/login', function () { return view('page2'); })->name('login');
Route::post('/login-check', [ScraperController::class, 'loginCheck'])->name('login.check');
Route::get('/logout', [ScraperController::class, 'logout'])->name('logout');

// Apsaugoti maršrutai
Route::middleware([])->group(function () {
    // Dashboard ir Botų valdymas
    Route::get('/dashboard', [ScraperController::class, 'dashboard'])->name('home');
    Route::post('/bots/add', [ScraperController::class, 'storeBot'])->name('bots.store');
    
    // SKREPINIMO MARŠRUTAS 
    Route::post('/run-scrape', [ScraperController::class, 'runScraper'])->name('run.scrape');
    Route::get('/run-scrape', [ScraperController::class, 'runScraper']); // GET palaikymui, jei reikia

    // Istorija ir duomenų valdymas
    Route::get('/history/sales', [ScraperController::class, 'showHistory'])->name('history.sales');
    Route::get('/history/messages', [ScraperController::class, 'showHistory'])->name('history.messages');
    
    Route::delete('/history/{id}', [ScraperController::class, 'destroy'])->name('history.destroy');
    Route::put('/history/{id}', [ScraperController::class, 'update'])->name('history.update');
    
    // Botų redagavimas ir trynimas
    Route::put('/bots/{id}', [ScraperController::class, 'updateBot'])->name('bots.update');
    Route::delete('/bots/{id}', [ScraperController::class, 'destroy'])->name('bots.destroy'); 
});