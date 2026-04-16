<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\AuthController; // Jei perkeltum loginą
use Illuminate\Http\Request;

// public
Route::get('/', function () {
    return view('page1');
})->name('info');

Route::get('/login', function () {
    return view('page2');
})->name('login');

Route::post('/login-check', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $email = $request->input('email');
    $password = $request->input('password');

    if (($email === env('ADMIN_EMAIL') && $password === env('ADMIN_PASSWORD')) || 
        ($email === env('USER2_EMAIL') && $password === env('USER2_PASSWORD'))) {
        session(['is_logged_in' => true, 'user_email' => $email]);
        return redirect()->route('home');
    }

    return back()->with('error', 'Incorrect credentials.');
})->name('login.check');


// safe
Route::middleware([])->group(function () {
    
    Route::get('/dashboard', function () {
        if (!session('is_logged_in')) return redirect()->route('login');
        return view('page3');
    })->name('home');

    // Skrepinimas
    Route::match(['get', 'post'], '/run-scrape', [ScraperController::class, 'runScraper'])->name('run.scrape');

    // Istorija
    Route::get('/history/sales', [ScraperController::class, 'showHistory'])->name('history.sales');
    Route::get('/history/messages', [ScraperController::class, 'showHistory'])->name('history.messages');

    Route::delete('/history/{id}', [ScraperController::class, 'destroy'])->name('history.destroy');

    Route::get('/logout', function () {
        session()->forget(['is_logged_in', 'user_email']);
        return redirect()->route('info');
    })->name('logout');
});