<?php

namespace App\Http\Controllers;

use SergiX44\Nutgram\Nutgram;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    protected Nutgram $bot;

    public function __construct(Nutgram $bot)
    {
        $this->bot = $bot;
    }

    public function handle(Request $request): JsonResponse
    {
        try {
            // Nutgram automatically handles the webhook payload
            $this->bot->run();

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Telegram webhook error: ' . $e->getMessage(), [
                'exception' => $e,
                'payload' => $request->all()
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }
}
