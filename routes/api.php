<?php

use App\Http\Controllers\Api\TelegramCatalogController;
use App\Http\Controllers\InstagramWebhookController;
use App\Http\Controllers\WhatsAppWebhookController;
use App\Http\Middleware\VerifyWhatsAppSignature;
use Illuminate\Support\Facades\Route;
use SergiX44\Nutgram\Nutgram;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Webhook routes for messaging channels
// Telegram webhook - Nutgram auto-handles webhook vs polling detection
Route::post('/webhook/telegram', function (Nutgram $bot) {
    try {
        $bot->run();

        return response()->json(['ok' => true]);
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Telegram webhook error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json(['ok' => false, 'error' => $e->getMessage()], 200);
    }
});

// WhatsApp webhook (GET for verification, POST for messages)
// GET request doesn't need signature verification (used for webhook setup)
Route::get('/webhook/whatsapp', [WhatsAppWebhookController::class, 'verify']);

// POST request MUST have signature verification to prevent spoofed messages
Route::post('/webhook/whatsapp', [WhatsAppWebhookController::class, 'handle'])
    ->middleware(VerifyWhatsAppSignature::class);

Route::post('/webhook/instagram', [InstagramWebhookController::class, 'handle']);

// Telegram Mini App API routes (with WebApp validation)
Route::prefix('telegram')->middleware('telegram.webapp')->group(function () {
    Route::get('/groups', [TelegramCatalogController::class, 'groups']);
    Route::get('/items', [TelegramCatalogController::class, 'items']);
    Route::get('/items/{groupSlug}', [TelegramCatalogController::class, 'items']);
    Route::get('/item/{slug}', [TelegramCatalogController::class, 'item']);
});
