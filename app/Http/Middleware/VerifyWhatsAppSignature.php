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

        // ==================== TEMPORARY: SIGNATURE VERIFICATION DISABLED ====================
        // Meta's Developer Console UI is currently broken and cannot display App Secret
        // Once you can access the App Secret, uncomment the code below to enable verification
        //
        // TO RE-ENABLE:
        // 1. Get App Secret from Meta Developers Console > Settings > Basic
        // 2. Add to .env: WHATSAPP_APP_SECRET=your_secret_here
        // 3. Uncomment the verification code below
        // 4. Comment out or remove the "BYPASSED" logging section
        // ===================================================================================

        $signature = $request->header('X-Hub-Signature-256');
        $payload = $request->getContent();
        $appSecret = config('services.whatsapp.app_secret');

        // Log everything for manual verification later
        Log::warning('⚠️ WhatsApp webhook signature verification BYPASSED (Meta UI broken)', [
            'ip' => $this->getClientIp($request),
            'received_signature' => $signature,
            'payload' => $payload,
            'payload_length' => strlen($payload),
            'app_secret_configured' => ! empty($appSecret),
            'app_secret_length' => $appSecret ? strlen($appSecret) : 0,
            'app_secret_value' => $appSecret ?: 'NOT_SET',
            'headers' => $request->headers->all(),
            'help' => 'Once Meta UI works, get App Secret and uncomment verification code in VerifyWhatsAppSignature middleware',
        ]);

        // BYPASSED: Allow all requests through without verification
        return $next($request);

        // ==================== COMMENTED OUT: RE-ENABLE WHEN APP SECRET IS AVAILABLE ====================
        /*
        $appSecret = config('services.whatsapp.app_secret');

        if (! $appSecret) {
            Log::warning('WhatsApp App Secret is not configured. Webhook signature verification is DISABLED. This is a security risk!');

            return $next($request);
        }

        $signature = $request->header('X-Hub-Signature-256');

        if (! $signature) {
            Log::error('WhatsApp webhook request missing X-Hub-Signature-256 header', [
                'headers' => $request->headers->all(),
                'ip' => $this->getClientIp($request),
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
                'ip' => $this->getClientIp($request),
                'payload_length' => strlen($payload),
                'app_secret_length' => strlen($appSecret),
                'app_secret_prefix' => substr($appSecret, 0, 8).'...',
                'help' => 'Verify WHATSAPP_APP_SECRET in .env matches App Secret in Meta Developers Console > Settings > Basic',
            ]);

            return response()->json([
                'error' => 'Invalid signature',
            ], Response::HTTP_UNAUTHORIZED);
        }

        Log::info('WhatsApp webhook signature verified successfully', [
            'ip' => $this->getClientIp($request),
        ]);

        return $next($request);
        */
        // ==============================================================================================
    }

    /**
     * Get the real client IP address (handles proxies, load balancers, and Docker)
     */
    protected function getClientIp(Request $request): string
    {
        // Check for forwarded IP (from proxies, load balancers, Docker)
        if ($request->header('X-Forwarded-For')) {
            $ips = explode(',', $request->header('X-Forwarded-For'));

            return trim($ips[0]); // First IP is the original client
        }

        if ($request->header('X-Real-IP')) {
            return $request->header('X-Real-IP');
        }

        // Fallback to Laravel's default (may be Docker internal IP)
        return $request->ip();
    }
}
