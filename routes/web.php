<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
   return csrf_token();
});

Route::post('/home', function () {
   return csrf_token();
});
