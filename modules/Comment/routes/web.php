<?php

use Illuminate\Support\Facades\Route;
use Modules\Comment\Controllers\CommentController;

Route::prefix('posts')->middleware('auth')->name('post.comment.')->group(function () {
    Route::get('/{post}', [CommentController::class, 'show'])->name('show');
    Route::post('/{post}/comment', [CommentController::class, 'store'])->name('store');
});


Route::prefix('dashboard')->middleware(['auth', 'admin'])->name('dashboard.')->group(function () {
    Route::post('/comments/{comment}/toggle-approved', [CommentController::class, 'toggleApproved'])->name('comments.toggleApproved');
    Route::resource('comments', CommentController::class)->except('store', 'show');
});