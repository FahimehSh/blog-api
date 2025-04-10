<?php

namespace App\Interfaces;

interface NotificationBotInterface {
    public function getUpdates(): array;
    public function sendMessage($chat_id, $message): array;
}
