<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\TelegramNotificationService;

class PostObserver
{
    protected $telegramNotificationService;

    public function __construct(TelegramNotificationService $telegramNotificationService)
    {
        $this->telegramNotificationService = $telegramNotificationService;
    }
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post)
    {
        if ($post->isDirty('status') && $post->status === 'published') {
            $author = $post->author;
            if ($author && $author->telegram_chat_id) {
                $this->telegramNotificationService->sendMessage($author->telegram_chat_id, 'پست شما منتشر شد.');
            }
        }
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
