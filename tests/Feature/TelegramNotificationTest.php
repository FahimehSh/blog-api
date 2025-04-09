<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramNotificationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_the_application_sends_telegram_notification_when_post_is_published(): void
    {
        $telegram_chat_id = 1735747527;

        $post = Post::factory()->create([
            'title' => 'test title',
            'content' => 'hjhsjhsjhjhjshadjjjkdajsjjas',
            'category_id' => 1,
        ]);
        $post->update(['status' => 'published']);

        $response = Http::post('https://api.telegram.org/bot' . config('services.telegram-bot-api.token') . '/sendMessage',
            ['form_params' => [
                'chat_id' => $telegram_chat_id,
                'text' => 'ok!',
            ]]);

        $response->assertStatus(200);
    }
}
