<?php

use App\Http\Controllers\QRcodeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return csrf_token();
});

Route::get('/check', function () {
    return csrf_token();
});

Route::post('/generate', [QRcodeController::class, 'generate']);
Route::get('/redirect/{id}', [App\Http\Controllers\QRcodeController::class, 'redirect']);
Route::delete('/deleted/{id}', [App\Http\Controllers\QRcodeController::class, 'deleted']);
Route::post('/edit/{id}', [App\Http\Controllers\QRcodeController::class, 'edit']);
Route::post('/create', [App\Http\Controllers\QRcodeController::class, 'create']);
Route::get('/check', [App\Http\Controllers\QRcodeController::class, 'check']);
Route::get('/read', [App\Http\Controllers\QRcodeController::class, 'read']);
Route::get('/UnDeleted', [App\Http\Controllers\QRcodeController::class, 'UnDeleted']);
