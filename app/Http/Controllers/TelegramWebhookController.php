<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    protected TelegramService $telegramService;
    protected CatalogService $catalogService;

    public function __construct(TelegramService $telegramService, CatalogService $catalogService)
    {
        $this->telegramService = $telegramService;
        $this->catalogService = $catalogService;
    }

    public function handle(Request $request)
    {
        // Simple check for a message update
        if (!$request->has('message')) {
            return response()->json(['status' => 'ok']);
        }

        $message = $request->input('message');
        $chatId = $message['chat']['id'];
        $text = trim($message['text'] ?? '');

        Log::info("Telegram Webhook received message from {$chatId}: '{$text}'");

        if (empty($text)) {
            return response()->json(['status' => 'ok']);
        }

        // Command parsing
        $parts = explode(' ', $text);
        $command = strtolower($parts[0]);
        $argument = $parts[1] ?? null;

        try {
            switch ($command) {
                case '/start':
                case '/catalog':
                    $this->telegramService->sendCatalog($chatId);
                    break;
                case '/groups':
                    $this->telegramService->sendGroups($chatId);
                    break;
                case '/items':
                    $groupSlug = $argument;
                    $this->telegramService->sendItems($chatId, $groupSlug);
                    break;
                case '/item':
                    $itemSlug = $argument;
                    $item = $this->catalogService->getItem($itemSlug);
                    if ($item) {
                        $this->telegramService->sendItemDetails($chatId, $item);
                    } else {
                        $this->telegramService->sendMessage($chatId, "Item '{$itemSlug}' not found.");
                    }
                    break;
                default:
                    $this->telegramService->sendMessage($chatId, "Unknown command. Try /catalog, /groups, or /items.");
                    break;
            }
        } catch (\Exception $e) {
            Log::error("Telegram Webhook Error: " . $e->getMessage());
            $this->telegramService->sendMessage($chatId, "An error occurred while processing your request.");
        }

        return response()->json(['status' => 'ok']);
    }
}
