<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::loginUsingId(1);

Route::post('register', [AuthController::class, 'register']);
Route::post('confirm', [AuthController::class, 'confirmVerificationCode']);
Route::post('login', [AuthController::class, 'login']);

Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::post('/create', [CategoryController::class, 'store'])->middleware(CheckAdmin::class)->name('store');

    Route::prefix('{category}')->group(function () {
        Route::get('/show', [CategoryController::class, 'show'])->name('show');
        Route::middleware(CheckAdmin::class)->group(function () {
            Route::put('/update', [CategoryController::class, 'update'])->name('update');
            Route::delete('/delete', [CategoryController::class, 'destroy'])->name('destroy');
        });
    });

    Route::post('/', [CategoryController::class, 'store'])->name('store');
});

Route::prefix('posts')->name('posts.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::post('/create', [PostController::class, 'store'])->name('store');

    Route::prefix('{post}')->group(function () {
        Route::get('/show', [PostController::class, 'show'])->name('show');
        Route::put('/update', [PostController::class, 'update'])->name('update');
        Route::delete('/delete', [PostController::class, 'destroy'])->name('destroy');
        Route::post('/like', [PostController::class, 'like'])->name('like');
        Route::delete('/unlike', [PostController::class, 'unlike'])->name('unlike');
        Route::post('/bookmark', [PostController::class, 'bookmark'])->name('bookmark');
        Route::delete('/unbookmark', [PostController::class, 'unbookmark'])->name('unbookmark');
    });
});

Route::prefix('comments')->name('comments.')->group(function () {
    Route::get('/', [CommentController::class, 'index'])->name('index');
    Route::post('/create-post-comments/{post}', [CommentController::class, 'storePostComments'])->name('createPostComments');

    Route::prefix('{comment}')->group(function () {
        Route::get('/show', [CommentController::class, 'show'])->name('show');
        Route::put('/update', [CommentController::class, 'update'])->name('update');
        Route::delete('/delete', [CommentController::class, 'destroy'])->name('destroy');
        Route::post('/like', [CommentController::class, 'like'])->name('like');
        Route::delete('/unlike', [CommentController::class, 'unlike'])->name('unlike');
    });
});

Route::get('/telegram-chat-id', [UserController::class, 'getTelegramChatId'])->name('users.getTelegramChatId');

