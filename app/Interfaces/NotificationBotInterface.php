<?php

namespace App\Interfaces;

interface NotificationBotInterface {
    public function getChatId(): array;
    public function sendMessage($chat_id, $message): array;
}
