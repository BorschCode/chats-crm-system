<?php

use App\Http\Controllers\InstagramWebhookController;
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\WhatsAppWebhookController;
use App\Http\Middleware\VerifyWhatsAppSignature;
use Illuminate\Support\Facades\Route;

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
Route::post('/webhook/telegram', [TelegramWebhookController::class, 'handle']);

// WhatsApp webhook (GET for verification, POST for messages)
// GET request doesn't need signature verification (used for webhook setup)
Route::get('/webhook/whatsapp', [WhatsAppWebhookController::class, 'verify']);

// POST request MUST have signature verification to prevent spoofed messages
Route::post('/webhook/whatsapp', [WhatsAppWebhookController::class, 'handle'])
    ->middleware(VerifyWhatsAppSignature::class);

Route::post('/webhook/instagram', [InstagramWebhookController::class, 'handle']);
