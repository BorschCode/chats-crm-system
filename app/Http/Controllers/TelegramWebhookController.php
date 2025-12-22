<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request, Nutgram $bot): JsonResponse
    {
        try {
            Log::info('Telegram webhook received', [
                'payload' => $request->all(),
            ]);

            // Run the bot with the incoming webhook update
            $bot->run();

            return response()->json(['ok' => true]);
        } catch (\Throwable $exception) {
            Log::error('Telegram webhook error: '.$exception->getMessage(), [
                'exception' => $exception,
                'payload' => $request->all(),
                'trace' => $exception->getTraceAsString(),
            ]);

            // Return 200 to prevent Telegram from retrying
            return response()->json(['ok' => false, 'error' => $exception->getMessage()], 200);
        }
    }
}
