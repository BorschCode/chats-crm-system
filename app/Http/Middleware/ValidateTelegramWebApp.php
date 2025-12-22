<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ValidateTelegramWebApp
{
    /**
     * Handle an incoming request and validate Telegram WebApp init data
     */
    public function handle(Request $request, Closure $next): Response
    {
        $initData = $request->header('X-Telegram-Init-Data') ?? $request->input('_auth');

        // Allow requests without auth in local development
        if (! $initData && app()->environment('local')) {
            Log::warning('Telegram WebApp validation skipped (local development)');

            return $next($request);
        }

        if (! $initData) {
            Log::warning('Missing Telegram init data');

            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        if (! $this->validateTelegramData($initData)) {
            Log::warning('Invalid Telegram init data');

            return response()->json(['error' => 'Invalid authentication'], Response::HTTP_UNAUTHORIZED);
        }

        // Parse and attach user data to request
        parse_str($initData, $data);
        $userData = json_decode($data['user'] ?? '{}', true);

        $request->merge([
            'telegramUser' => $userData,
            'telegramAuthDate' => $data['auth_date'] ?? null,
            'telegramHash' => $data['hash'] ?? null,
        ]);

        Log::info('Telegram WebApp request authenticated', [
            'user_id' => $userData['id'] ?? null,
            'username' => $userData['username'] ?? null,
        ]);

        return $next($request);
    }

    /**
     * Validate Telegram WebApp init data according to official algorithm
     *
     * @see https://core.telegram.org/bots/webapps#validating-data-received-via-the-mini-app
     */
    protected function validateTelegramData(string $initData): bool
    {
        $botToken = config('services.telegram.bot_token');

        if (! $botToken || $botToken === 'dummy-token') {
            Log::error('Telegram bot token not configured');

            return false;
        }

        parse_str($initData, $data);
        $hash = $data['hash'] ?? '';
        unset($data['hash']);

        // Sort data alphabetically
        ksort($data);

        // Create data-check-string
        $dataCheckArray = [];
        foreach ($data as $key => $value) {
            $dataCheckArray[] = $key.'='.$value;
        }
        $dataCheckString = implode("\n", $dataCheckArray);

        // Generate secret key
        $secretKey = hash_hmac('sha256', $botToken, 'WebAppData', true);

        // Calculate hash
        $calculatedHash = bin2hex(hash_hmac('sha256', $dataCheckString, $secretKey, true));

        // Verify hash
        return hash_equals($calculatedHash, $hash);
    }
}
