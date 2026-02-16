<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('page1');
});

Route::get('/page2', function () {
    return view('page2');
});