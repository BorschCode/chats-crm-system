<?php

namespace App\Telegram\Middleware;

use App\Models\User;
use Illuminate\Support\Facades\App;
use SergiX44\Nutgram\Nutgram;

class SetUserLocaleMiddleware
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $chatId = (string) $bot->chatId();

        $user = User::byTelegramChatId($chatId)->first();

        if ($user && $user->language) {
            App::setLocale($user->language->value);
        }

        $next($bot);
    }
}
