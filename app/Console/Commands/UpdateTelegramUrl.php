<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateTelegramUrl extends Command
{
    protected $signature = 'telegram:update-url {url? : The new base URL (defaults to APP_URL)}';

    protected $description = 'Update Telegram bot menu button to the current APP_URL (run after every ngrok restart)';

    public function handle(): int
    {
        $baseUrl = $this->argument('url') ?? config('app.url');
        $miniAppUrl = rtrim($baseUrl, '/').'/telegram/app';
        $token = config('services.telegram.bot_token');

        if (! $token) {
            $this->error('TELEGRAM_TOKEN is not configured.');

            return self::FAILURE;
        }

        $this->info("Setting Telegram menu button URL to: {$miniAppUrl}");

        $response = Http::post("https://api.telegram.org/bot{$token}/setChatMenuButton", [
            'menu_button' => [
                'type' => 'web_app',
                'text' => '🛍 Open Catalog',
                'web_app' => ['url' => $miniAppUrl],
            ],
        ]);

        if ($response->json('ok')) {
            $this->info('✓ Menu button updated successfully.');
            $this->newLine();
            $this->warn('Remember: also update BotFather manually for the Main Web App URL:');
            $this->line('  1. Open @BotFather → /mybots → select your bot');
            $this->line("  2. Bot Settings → Main Web App → set URL to: {$miniAppUrl}");

            return self::SUCCESS;
        }

        $this->error('Failed: '.$response->body());

        return self::FAILURE;
    }
}
