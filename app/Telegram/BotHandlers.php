<?php

namespace App\Telegram;

use App\Services\CatalogService;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class BotHandlers
{
    protected CatalogService $catalogService;

    protected TelegramService $telegramService;

    public function __construct(
        CatalogService $catalogService,
        TelegramService $telegramService
    ) {
        $this->catalogService = $catalogService;
        $this->telegramService = $telegramService;
    }

    public function registerHandlers(Nutgram $bot): void
    {
        // Start command - Launch Mini App
        $bot->onCommand('start', function (Nutgram $bot) {
            $bot->sendMessage(
                text: "Welcome! 👋\n\nTap the button below to browse our catalog in an interactive app.",
                reply_markup: InlineKeyboardMarkup::make()->addRow(
                    InlineKeyboardButton::make(
                        '🛍️ Open Catalog',
                        web_app: ['url' => config('app.url').'/telegram/app']
                    )
                )
            );
        });

        // Catalog command - Launch Mini App
        $bot->onCommand('catalog', function (Nutgram $bot) {
            $bot->sendMessage(
                text: '🛍️ Opening catalog...',
                reply_markup: InlineKeyboardMarkup::make()->addRow(
                    InlineKeyboardButton::make(
                        '🛍️ Open Catalog',
                        web_app: ['url' => config('app.url').'/telegram/app']
                    )
                )
            );
        });

        // Groups command
        $bot->onCommand('groups', function (Nutgram $bot) {
            $chatId = (string) $bot->chatId();
            $this->telegramService->sendGroups($chatId);
        });

        // Items command (with optional group filter)
        $bot->onCommand('items {groupSlug?}', function (Nutgram $bot, ?string $groupSlug = null) {
            $chatId = (string) $bot->chatId();
            $this->telegramService->sendItems($chatId, $groupSlug);
        });

        // Item details command
        $bot->onCommand('item {itemSlug}', function (Nutgram $bot, string $itemSlug) {
            $chatId = (string) $bot->chatId();
            $item = $this->catalogService->getItem($itemSlug);

            if ($item) {
                $this->telegramService->sendItemDetails($chatId, $item);
            } else {
                $this->telegramService->sendMessage($chatId, "Item '{$itemSlug}' not found.");
            }
        });

        // Fallback command handler
        $bot->fallback(function (Nutgram $bot) {
            $chatId = (string) $bot->chatId();
            $this->telegramService->sendMessage(
                $chatId,
                "Unknown command. Try:\n/catalog\n/groups\n/items\n/item {slug}"
            );
        });

        // Exception handler
        $bot->onException(function (Nutgram $bot, \Throwable $exception) {
            Log::error('Telegram bot error: '.$exception->getMessage(), [
                'exception' => $exception,
                'update' => $bot->update(),
            ]);

            try {
                $bot->sendMessage(
                    text: 'An error occurred while processing your request. Please try again later.',
                    chat_id: $bot->chatId()
                );
            } catch (\Exception $e) {
                Log::error('Failed to send error message: '.$e->getMessage());
            }
        });
    }
}
