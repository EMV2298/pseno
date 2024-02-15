<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\TypeContent;
use App\Models\User;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::get('/user/{id}', function ($id) {
    return new UserResource(User::findOrFail($id));
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