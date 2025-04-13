<?php

namespace Tests\Feature;

use AllowDynamicProperties;
use App\Models\User;
use App\Notifications\PostPublishedTelegramNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

#[AllowDynamicProperties] class TelegramNotificationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_telegram_notification_sent_successfully(): void
    {
        $data = [
            "ok" => true,
            "result" => [
                "message_id" => 19,
                "from" => [
                    "id" => 7344438601,
                    "is_bot" => true,
                    "first_name" => "new channel for notifications",
                    "username" => "new_blog_notif_bot",
                ],
                "chat" => [
                    "id" => 1735747527,
                    "first_name" => "Fahimeh",
                    "last_name" => "Shirdel",
                    "type" => "private"
                ],
                "date" => 1744543619,
                "text" => "پست شما منتشر شد."
            ]
        ];

        Http::fake([
            'https://api.telegram.org/*' => Http::response($data, 200),
        ]);

        $user = User::query()->where('telegram_chat_id', 1735747527)->first();

        $user->notify(new PostPublishedTelegramNotification());

        Notification::assertSentTo([$user], PostPublishedTelegramNotification::class);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'sendMessage')
                && $request['chat_id'] == 1735747527
                && $request['text'] == 'پست شما منتشر شد.';
        });
    }

    public function test_telegram_notification_only_accepts_string_text(): void
    {
        Http::fake([
            'https://api.telegram.org/*' => Http::response(['status' => 'error'], 500),
        ]);

        $response = Http::post('https://api.telegram.org/bot' . config('services.telegram-bot-api.token') . '/sendMessage', [
            'chat_id' => 1735747527,
            'text' => 13132131
        ]);

        $this->assertEquals(500, $response->status());
    }

    public function test_telegram_notification_when_telegram_returns_empty_array(): void

    {
        Http::fake([
            'https://api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $response = Http::post('https://api.telegram.org/bot' . config('services.telegram-bot-api.token') . '/sendMessage', [
            'chat_id' => 1735747527,
            'text' => 'پست شما منتشر شد.'
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function test_telegram_notification_when_telegram_returns_500_error(): void
    {
        Http::fake([
            'https://api.telegram.org/*' => Http::response(null, 500),
        ]);

        $response = Http::post('https://api.telegram.org/bot' . config('services.telegram-bot-api.token') . '/sendMessage', [
            'chat_id' => 1735747527,
            'text' => 'پست شما منتشر شد.'
        ]);

        $this->assertEquals(500, $response->status());
    }
}
