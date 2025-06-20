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

Route::post('/generate',[qrcodeController::class, 'generate']);
Route::post('/links', [\App\Http\Controllers\Controller_22::class, 'profile'])->name('l1');
//Route::post('/links_2', [\App\Http\Controllers\Controller_22::class, 'profile']);
Route::post('/links_2', [\App\Http\Controllers\Controller_22::class, 'profile2']);

Route::get('/wrong',[\App\Http\Controllers\Controller_22::class, 'wrong']);
Route::get('/correct',[\App\Http\Controllers\Controller_22::class, 'correct']);

Route::get('/redir/{id}', [App\Http\Controllers\Controller_22::class, 'redirect']);
Route::get('/read/{id}', [App\Http\Controllers\QRcodeController::class, 'redirect']);
Route::delete('/deleted/{id}', [App\Http\Controllers\Controller_22::class, 'deleted']);
Route::put('/edit/{id}', [App\Http\Controllers\QRcodeController::class, 'edit']);
Route::post('/create', [App\Http\Controllers\QRcodeController::class, 'create']);
Route::get('/check',[App\Http\Controllers\QRcodeController::class, 'check']);

Route::get('/read', [App\Http\Controllers\QRcodeController::class, 'read']);
Route::get('/testJson', [App\Http\Controllers\Controller_22::class, 'testJson']);
