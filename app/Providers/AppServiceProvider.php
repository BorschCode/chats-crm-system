<?php

namespace App\Providers;

use App\Services\CatalogService;
use App\Services\TelegramService;
use App\Telegram\BotHandlers;
use Illuminate\Support\ServiceProvider;
use SergiX44\Nutgram\Nutgram;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Nutgram bot as singleton (only if token is configured)
        $this->app->singleton(Nutgram::class, function ($app) {
            $config = config('services.telegram');
            $token = $config['bot_token'] ?? null;

            // Provide a dummy token if not configured to prevent crashes
            if (! $token || $token === 'your_bot_token_from_@BotFather') {
                $token = 'dummy-token-for-development';
            }

            return new Nutgram($token);
        });

        // Register BotHandlers as singleton
        $this->app->singleton(BotHandlers::class, function ($app) {
            return new BotHandlers(
                $app->make(CatalogService::class),
                $app->make(TelegramService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Telegram bot handlers
        if (config('services.telegram.bot_token')) {
            $bot = $this->app->make(Nutgram::class);
            $handlers = $this->app->make(BotHandlers::class);
            $handlers->registerHandlers($bot);
        }
    }
}
