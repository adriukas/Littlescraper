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
    if (!session('is_logged_in')) {
        return redirect('/page2')->with('error', 'Please log in first.');}
    return view('page3');
})->name('home');

Route::match(['get', 'post'], '/run-scrape', [ScraperController::class, 'runScraper'])->name('run.scrape');

Route::get('/page4', function (Request $request) {
    if (!session('is_logged_in')) {
        return redirect('/page2')->with('error', 'Please log in first.');
    }
    $botName = $request->query('bot', 'Unknown Bot');
    $botType = $request->query('type');
    
    $channelId = env("ID_" . $botType);

    return view('page4', [
        'botName' => $botName,
        'channelId' => $channelId
    ]); })->name('scraper');

Route::post('/login-check', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');

    $isAdmin = ($email === env('ADMIN_EMAIL') && $password === env('ADMIN_PASSWORD'));
    
    $isUser2 = ($email === env('USER2_EMAIL') && $password === env('USER2_PASSWORD'));

    if ($isAdmin || $isUser2) {
        session(['is_logged_in' => true, 'user_email' => $email]);
        return redirect('/page3');
    }

    return back()->with('error', 'Invalid email or password.');
});

Route::get('/history_sales', [ScraperController::class, 'showHistory'])->name('history.sales');
Route::get('/history_messages', [ScraperController::class, 'showHistory'])->name('history.messages');
