<?php

namespace Modules\Category\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function map()
    {
        Route::middleware('web')
            ->group(__DIR__ . '/../routes/web.php');
    }
}
