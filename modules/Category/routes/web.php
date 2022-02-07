<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Controller\CategoryController;

Route::prefix('dashboard')->middleware(['auth', 'admin'])->name('dashboard.')->group(function () {
    Route::resource('categories', CategoryController::class);
});
