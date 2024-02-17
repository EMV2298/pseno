<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/user', 'getOne');
    Route::get('user/like', 'like');
    Route::get('user/subscription', 'subscription');
});

Route::controller(PostController::class)->group(function () {
    Route::get('/post', 'getOne');
    Route::get('/post/feed', 'feed');
    Route::get('/post/user', 'byUser');
});