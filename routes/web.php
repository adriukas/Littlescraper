<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScraperController;
use Illuminate\Http\Request;

// PUBLIC ROUTES 
Route::get('/', function () {
    return view('page1');
})->name('info');

Route::get('/page2', function () {
    return view('page2');
})->name('login');

Route::post('/login-check', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');

    $isAdmin = ($email === env('ADMIN_EMAIL') && $password === env('ADMIN_PASSWORD'));
    $isUser2 = ($email === env('USER2_EMAIL') && $password === env('USER2_PASSWORD'));

    if ($isAdmin || $isUser2) {
        session(['is_logged_in' => true, 'user_email' => $email]);
        return redirect()->route('home');
    }

    return back()->with('error', 'Invalid email or password.');
});

// PROTECTED ROUTES 
Route::group([], function () {

    Route::get('/page3', function () {
        if (!session('is_logged_in')) return redirect()->route('login');
        return view('page3');
    })->name('home');

    // Svarbiausias maršrutas
    Route::match(['get', 'post'], '/run-scrape', [ScraperController::class, 'runScraper'])->name('run.scrape');

    Route::get('/history_sales', [ScraperController::class, 'showHistory'])->name('history.sales');
    Route::get('/history_messages', [ScraperController::class, 'showHistory'])->name('history.messages');

    Route::get('/logout', function () {
        session()->forget(['is_logged_in', 'user_email']);
        return redirect()->route('info');
    })->name('logout');
});