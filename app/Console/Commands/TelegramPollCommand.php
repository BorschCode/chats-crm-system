<?php

namespace App\Console\Commands;

use SergiX44\Nutgram\Nutgram;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TelegramPollCommand extends Command
{
    protected $signature = 'telegram:poll
                          {--timeout=60 : Polling timeout in seconds}
                          {--limit=100 : Maximum number of updates to fetch}';

    protected $description = 'Start Telegram bot in polling mode (for local development)';

    protected Nutgram $bot;

    public function __construct(Nutgram $bot)
    {
        parent::__construct();
        $this->bot = $bot;
    }

    public function handle(): int
    {
        if (config('services.telegram.mode') !== 'polling') {
            $this->error('Telegram bot is not configured for polling mode.');
            $this->info('Set TELEGRAM_MODE=polling in your .env file.');
            return self::FAILURE;
        }

        $this->info('Starting Telegram bot in polling mode...');
        $this->info('Press Ctrl+C to stop.');

        try {
            // Delete webhook if it exists (required for polling)
            $this->bot->deleteWebhook(['drop_pending_updates' => false]);

            $this->info('Webhook removed. Bot is now polling for updates.');

            // Start polling
            $this->bot->run();

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Polling error: ' . $e->getMessage());
            Log::error('Telegram polling error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return self::FAILURE;
        }
    }
}
