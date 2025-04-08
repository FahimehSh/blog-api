<?php

namespace App\Services;

use App\Interfaces\NotificationBotInterface;
use GuzzleHttp\Client;

class TelegramNotificationService implements NotificationBotInterface
{
    public function getChatId(): array
    {
        $telegram_bot_token = config('services.telegram-bot-api.token');
        $client = new Client();
        $res = $client->post("https://api.telegram.org/bot{$telegram_bot_token}/getUpdates", []);

        $response = json_decode($res->getBody()->getContents(), true);

        return $response;
    }

    public function sendMessage($chat_id, $message): array
    {
        $telegram_bot_token = config('services.telegram-bot-api.token');
        $client = new Client();
        $res = $client->post("https://api.telegram.org/bot{$telegram_bot_token}/sendMessage",
            ['form_params' => [
                'chat_id' => $chat_id,
                'text' => $message,
            ]]);

        $response = json_decode($res->getBody()->getContents(), true);

        return $response;
    }
}
