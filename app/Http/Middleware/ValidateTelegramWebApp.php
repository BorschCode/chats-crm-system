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
        Log::info('INIT_DATA_DEBUG', [
            'raw_header' => $request->header('X-Telegram-Init-Data'),
            'init_data_length' => strlen((string) $request->header('X-Telegram-Init-Data')),
            'version' => $request->header('X-Telegram-Version'),
        ]);

        // Use ?: so nginx's empty string injection falls through to _auth query param
        $initData = $request->header('X-Telegram-Init-Data') ?: $request->input('_auth');

        // No initData — allow as anonymous Telegram user (catalog is read-only public data).
        // Old Telegram Desktop clients (WebApp API v6.0) pass initDataUnsafe but not the
        // raw initData string, so blocking here would break the catalog for those users.
        if (! $initData) {
            Log::info('Telegram WebApp: no initData, continuing as anonymous');

            return $next($request);
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
