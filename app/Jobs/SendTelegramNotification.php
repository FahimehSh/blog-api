<?php

namespace App\Jobs;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Services\TelegramNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTelegramNotification implements ShouldQueue
{
    use Queueable;

    protected TelegramNotificationService $telegramNotificationService;
    protected Post $post;

    public function __construct(TelegramNotificationService $telegramNotificationService, Post $post)
    {
        $this->telegramNotificationService = $telegramNotificationService;
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->post->status === PostStatus::PUBLISHED->value) {
            $author = $this->post->author;
            if ($author && $author->telegram_chat_id) {
                $this->telegramNotificationService->sendMessage($author->telegram_chat_id, 'پست شما منتشر شد.');
            }
        }
    }
}
