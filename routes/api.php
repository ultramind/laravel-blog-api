<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// register route/endpoint
Route::post('/register', [AuthController::class, 'registerUser']);

// login route/endpoint
Route::post('/login', [AuthController::class, 'loginUser']);