<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/chat/messages/{userId}', [ChatController::class, 'getMessages']);
Route::post('/chat/send', [ChatController::class, 'sendMessage']);
Route::post('/login', [UserController::class, 'login']);