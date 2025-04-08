<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Repositories\CommentRepository;
use App\Models\Repositories\PostRepository;
use App\Observers\PostObserver;
use App\Services\CommentService;
use App\Services\PostService;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\NotificationBotInterface;
use App\Services\TelegramNotificationService;

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
        $this->app->singleton('NotificationBotInterface', TelegramNotificationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Post::observe(PostObserver::class);
    }
}
