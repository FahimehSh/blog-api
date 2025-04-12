<?php

namespace App\Interfaces;

interface NotificationBotInterface {
    public function getUpdatescontainedChatId(): array;
    public function sendMessage($chat_id, $message): array;
}
