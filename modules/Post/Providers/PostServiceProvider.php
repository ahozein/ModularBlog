<?php

namespace Modules\Post\Providers;

use App\Models\User;
use Modules\Post\Models\Post;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider
{
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        User::has_many('posts', Post::class);
    }
}