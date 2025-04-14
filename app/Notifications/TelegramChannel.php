<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class TelegramChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $telegram_bot_token = config('services.telegram-bot-api.token');
        Http::post("https://api.telegram.org/bot{$telegram_bot_token}/sendMessage",
            [
                'chat_id' => $notifiable->telegram_chat_id,
                'text' => 'پست شما منتشر شد.',
            ]);
    }
}
