<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScraperController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Bot; 

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
    
    $user = User::where('email', $email)->first();
    
    if ($user && \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
        Auth::login($user); 
        session(['is_logged_in' => true, 'user_email' => $email]);
        return redirect()->route('home');
    }

    return back()->with('error', 'Incorrect credentials.');
})->name('login.check');

// safe
Route::middleware([])->group(function () {
    
        Route::get('/dashboard', function () {
            if (!session('is_logged_in')) return redirect()->route('login');
            $bots = \App\Models\Bot::all(); 
            return view('page3', compact('bots'));
        })->name('home');
    
    // we are adding new bot
    Route::post('/bots/add', function (Request $request) {
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

        return back()->with('success', 'Bot successfully updated or added!');
    })->name('bots.store');
    //deleting new bots
    Route::delete('/bots/{id}', function ($id) {
        $bot = \App\Models\Bot::find($id);
        
        if ($bot) {
            $bot->delete();
            return back()->with('success', 'Bot removed from database.');
        }
        
        return back()->with('error', 'Bot not found.');
    })->name('bots.destroy');

    Route::match(['get', 'post'], '/run-scrape', [ScraperController::class, 'runScraper'])->name('run.scrape');
    Route::get('/history/sales', [ScraperController::class, 'showHistory'])->name('history.sales');
    Route::get('/history/messages', [ScraperController::class, 'showHistory'])->name('history.messages');
    Route::delete('/history/{id}', [ScraperController::class, 'destroy'])->name('history.destroy');
    Route::put('/history/{id}', [ScraperController::class, 'update'])->name('history.update');
Route::put('/bots/{id}', [ScraperController::class, 'updateBot'])->name('bots.update');
    Route::get('/logout', function () {
        Auth::logout();
        session()->forget(['is_logged_in', 'user_email']);
        return redirect()->route('info');
    })->name('logout');
});