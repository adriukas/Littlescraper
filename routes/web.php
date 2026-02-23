<?php
use Illuminate\Support\Facades\Route;

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