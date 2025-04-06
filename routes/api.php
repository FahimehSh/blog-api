<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('confirm', [AuthController::class, 'confirmVerificationCode']);
Route::post('login', [AuthController::class, 'login']);

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');
    Route::get('/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/', [PostController::class, 'store'])->name('posts.store');
    Route::put('/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/{post}/like', [PostController::class, 'unlike'])->name('posts.unlike');
    Route::post('/{post}/bookmark', [PostController::class, 'bookmark'])->name('posts.bookmark');
    Route::delete('/{post}/bookmark', [PostController::class, 'unbookmark'])->name('posts.unbookmark');
});

Route::prefix('comments')->group(function () {
    Route::get('/', [CommentController::class, 'index'])->name('comments.index');
    Route::get('/{comment}', [CommentController::class, 'show'])->name('comments.show');
    Route::post('/', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::delete('/{comment}/like', [CommentController::class, 'unlike'])->name('comments.unlike');
});
