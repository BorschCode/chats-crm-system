<?php

namespace App\Providers;

use App\Http\Integrations\WhatsApp\WhatsAppConnector;
use Illuminate\Support\ServiceProvider;

class WhatsAppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(WhatsAppConnector::class, function ($app) {
            $config = config('services.whatsapp');

            return new WhatsAppConnector(
                phoneNumberId: $config['phone_number_id'],
                accessToken: $config['access_token'],
                apiVersion: $config['api_version']
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
