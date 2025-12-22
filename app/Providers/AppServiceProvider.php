<?php

namespace App\Providers;

use App\Services\CatalogService;
use App\Services\TelegramService;
use App\Telegram\BotHandlers;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use SergiX44\Nutgram\Nutgram;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Nutgram::class, function ($app) {
            $config = config('services.telegram');
            $token = $config['bot_token'] ?? 'dummy-token';

            if ($token === 'your_bot_token_from_@BotFather') {
                $token = 'dummy-token';
            }

            return new Nutgram($token);
        });

        $this->app->singleton(BotHandlers::class, function ($app) {
            return new BotHandlers(
                $app->make(CatalogService::class),
                $app->make(TelegramService::class)
            );
        });
    }

    public function boot(): void
    {
        if (request()->header('X-Forwarded-Proto') === 'https' || request()->header('X-Forwarded-Ssl') === 'on') {
            URL::forceScheme('https');
        }

        if (config('services.telegram.bot_token')) {
            $bot = $this->app->make(Nutgram::class);
            $handlers = $this->app->make(BotHandlers::class);
            $handlers->registerHandlers($bot);
        }
    }
}
