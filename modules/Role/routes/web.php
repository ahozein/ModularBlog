<?php

use Modules\Role\Controllers\RoleController;

Route::prefix('dashboard')->middleware(['auth', 'admin'])->name('dashboard.')->group(function () {
    Route::resource('roles', RoleController::class);
});
