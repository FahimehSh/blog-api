<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramNotificationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_telegram_notification_done_successfully(): void
    {
        Http::fake([
            'https://api.telegram.org/*' => Http::response(['status' => 'success'], 200),
        ]);

        $response = Http::post('https://api.telegram.org/bot' . config('services.telegram-bot-api.token') . '/sendMessage', [
            'chat_id' => 1735747527,
            'text' => 'پست شما منتشر شد.'
        ]);

        $this->assertEquals(200, $response->status());
        $this->assertJsonStringEqualsJsonString(json_encode(['status' => 'success']), $response->body());
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
