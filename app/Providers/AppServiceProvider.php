<?php

namespace App\Providers;

use App\Repositories\CommentRepository;
use App\Repositories\PostRepository;
use App\Services\CommentService;
use App\Services\PostService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CommentRepository::class, CommentRepository::class);
        $this->app->bind(CommentService::class, CommentService::class);
        $this->app->bind(PostRepository::class, PostRepository::class);
        $this->app->bind(PostService::class, PostService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
