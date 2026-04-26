<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\ScraperController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('page1'))->name('info');
Route::get('/login', fn() => view('page2'))->name('login');
Route::post('/login-check', [AuthController::class, 'loginCheck'])->name('login.check');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [BotController::class, 'dashboard'])->name('home');
    Route::post('/bots/add', [BotController::class, 'store'])->name('bots.store');
    Route::put('/bots/{id}', [BotController::class, 'update'])->name('bots.update');
    Route::delete('/bots/{id}', [BotController::class, 'destroy'])->name('bots.destroy');

    Route::get('/run-scrape', [ScraperController::class, 'runScraper']);
    Route::post('/run-scrape', [ScraperController::class, 'runScraper'])->name('run.scrape');

    Route::get('/history/sales', [ScraperController::class, 'showHistory'])->name('history.sales');
    Route::get('/history/messages', [ScraperController::class, 'showHistory'])->name('history.messages');
    Route::delete('/history/{id}', [ScraperController::class, 'destroy'])->name('history.destroy');
    Route::put('/history/{id}', [ScraperController::class, 'update'])->name('history.update');
});
