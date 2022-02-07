<?php

use Modules\Post\Controllers\PostController;

Route::prefix('dashboard')->middleware(['auth', 'admin'])->name('dashboard.')->group(function () {
    Route::post('/posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])->name('posts.toggleStatus');
    Route::resource('posts', PostController::class);
});
