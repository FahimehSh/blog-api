<?php

namespace App\Http\Controllers;

use App\Services\TelegramNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected TelegramNotificationService $telegramNotificationService;

    public function __construct(TelegramNotificationService $telegramNotificationService)
    {
        $this->telegramNotificationService = $telegramNotificationService;
    }

    public function getTelegramChatId(): JsonResponse
    {
        $res = $this->telegramNotificationService->getUpdatescontainedChatId();
        $response_data = end($res['result']);
        $telegram_chat_id = $response_data['message']['chat']['id'];
        Auth::user()->telegram_chat_id = $telegram_chat_id;
        Auth::user()->save();

        return response()->json();
    }
}
