<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppWebhookService
{
    public function __construct(
        protected string $verifyToken
    ) {}

    /**
     * Verify webhook subscription request from WhatsApp
     */
    public function verifyWebhook(string $mode, string $token, string $challenge): ?string
    {
        Log::info('WhatsApp webhook verification attempt', [
            'received_mode' => $mode,
            'received_token' => $token,
            'expected_token' => $this->verifyToken,
            'challenge' => $challenge,
            'tokens_match' => $token === $this->verifyToken,
        ]);

        if ($mode === 'subscribe' && $token === $this->verifyToken) {
            Log::info('WEBHOOK VERIFIED');

            return $challenge;
        }

        Log::warning('WhatsApp webhook verification failed', [
            'mode' => $mode,
            'received_token' => $token,
            'expected_token' => $this->verifyToken,
        ]);

        return null;
    }
}
