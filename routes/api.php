<?php

use App\Http\Controllers\InstagramWebhookController;
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\WhatsAppWebhookController;
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
Route::post('/webhook/whatsapp', [WhatsAppWebhookController::class, 'handle']);
Route::post('/webhook/instagram', [InstagramWebhookController::class, 'handle']);
