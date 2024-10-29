<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/check-firebase', [FirebaseController::class, 'checkFirebaseConnection']);
Route::get('/user-list', [UserController::class, 'index']);

Route::get('/chat/{user}', [UserController::class, 'show'])->name('chat.show');
Route::get('/chat/{user}/messages', [UserController::class, 'getMessages'])->name('chat.messages');
Route::post('/chat/{user}/send', [UserController::class, 'sendMessage'])->name('chat.send');
