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
        // Nutgram singleton is registered by NutgramServiceProvider with proper configuration
        // DO NOT override it here to preserve cache and conversation persistence

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

        // Handler registration is done in routes/telegram.php via NutgramServiceProvider
        // DO NOT register handlers here to avoid double registration
    }
}
