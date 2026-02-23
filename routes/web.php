<?php
use Illuminate\Support\Facades\Route;

//pirmas puslapis
Route::get('/', function () {
    return view('page1');
});

//antras puslapis
Route::get('/page2', function () {
    return view('page2');
});

