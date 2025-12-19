<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyWhatsAppSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->method() !== Request::METHOD_POST) {
            return $next($request);
        }

        $appSecret = config('services.whatsapp.app_secret');

        if (! $appSecret) {
            Log::warning('WhatsApp App Secret is not configured. Webhook signature verification is DISABLED. This is a security risk!');

            return $next($request);
        }

        $signature = $request->header('X-Hub-Signature-256');

        if (! $signature) {
            Log::error('WhatsApp webhook request missing X-Hub-Signature-256 header', [
                'headers' => $request->headers->all(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'error' => 'Missing signature header',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $payload = $request->getContent();
        $expectedSignature = 'sha256='.hash_hmac('sha256', $payload, $appSecret);

        if (! hash_equals($expectedSignature, $signature)) {
            Log::error('WhatsApp webhook signature verification failed', [
                'received_signature' => $signature,
                'expected_signature' => $expectedSignature,
                'ip' => $request->ip(),
                'payload_length' => strlen($payload),
            ]);

            return response()->json([
                'error' => 'Invalid signature',
            ], Response::HTTP_UNAUTHORIZED);
        }

        Log::info('WhatsApp webhook signature verified successfully');

        return $next($request);
    }
}
