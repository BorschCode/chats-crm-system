<?php

namespace App\Providers;

use App\Http\Controllers\InstagramWebhookController;
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\WhatsAppWebhookController;
use App\Http\Integrations\WhatsApp\WhatsAppConnector;
use App\Services\CatalogService;
use App\Services\InstagramService;
use App\Services\MessagingService;
use App\Services\TelegramService;
use App\Services\WhatsAppService;
use App\Services\WhatsAppWebhookService;
use Illuminate\Support\ServiceProvider;
use SergiX44\Nutgram\Nutgram;

class MessagingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WhatsAppWebhookService::class, function ($app) {
            return new WhatsAppWebhookService(
                $app->make(CatalogService::class),
                config('services.whatsapp.webhook_verify_token')
            );
        });

        $this->app->singleton(WhatsAppService::class, function ($app) {
            return new WhatsAppService(
                $app->make(CatalogService::class),
                $app->make(WhatsAppConnector::class)
            );
        });

        $this->app->singleton(TelegramService::class, function ($app) {
            return new TelegramService(
                $app->make(CatalogService::class),
                $app->make(Nutgram::class)
            );
        });

        $this->app->singleton(InstagramService::class, function ($app) {
            return new InstagramService(
                $app->make(CatalogService::class)
            );
        });

        $this->app->when(WhatsAppWebhookController::class)
            ->needs(MessagingService::class)
            ->give(WhatsAppService::class);

        $this->app->when(TelegramWebhookController::class)
            ->needs(MessagingService::class)
            ->give(TelegramService::class);

        $this->app->when(InstagramWebhookController::class)
            ->needs(MessagingService::class)
            ->give(InstagramService::class);
    }
}
