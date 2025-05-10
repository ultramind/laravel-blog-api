<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// register route/endpoint
Route::post('/register', [AuthController::class, 'registerUser']);

// login route/endpoint
Route::post('/login', [AuthController::class, 'loginUser']);


//public routes/endpoints
Route::get('/all/posts', [PostController::class, 'getAllPosts']);
Route::get('/single/post/{post_id}', [PostController::class, 'singlePost']);

// protected route/endpoint
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logoutUser']);
    Route::post('/add/post', [PostController::class, 'addNewPost']);
    Route::post('/edit/post', [PostController::class, 'editPost']);
});