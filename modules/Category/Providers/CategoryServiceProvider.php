<?php

namespace Modules\Category\Providers;

use Modules\Post\Models\Post;
use Modules\Category\Models\Category;
use Illuminate\Support\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        Post::belongs_to('category', Category::class);
    }
}
