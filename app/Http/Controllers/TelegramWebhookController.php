<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use Symfony\Component\HttpFoundation\Response;

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
        } catch (\Exception $exception) {
            Log::error('Telegram webhook error: '.$exception->getMessage(), [
                'exception' => $exception,
                'payload' => $request->all(),
            ]);

            return response()->json(['status' => 'error'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
