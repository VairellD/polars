<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/gotoabout', function () {
    return view('about');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/upload', function () {
    return view('login');
});

Route::get('/getstarted', function () {
    return view('login');
});