<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\TypeContent;

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


Route::get('/post/{id}', function ($id) {
    return new PostResource(Post::findOrFail($id));
});

Route::get('/post', function () {
    return new PostCollection(Post::all());
});

Route::get('/type', function () {
    return TypeContent::all();
});