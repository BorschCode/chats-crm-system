<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class TelegramService implements MessagingService
{
    protected CatalogService $catalogService;

    protected Nutgram $bot;

    public function __construct(CatalogService $catalogService, Nutgram $bot)
    {
        $this->catalogService = $catalogService;
        $this->bot = $bot;
    }

    public function markReadAndSendTypingIndicator(string $messageId, string $recipient): void
    {
        // Telegram doesn't have a "mark as read" API
        // But it DOES support typing indicators via sendChatAction
        try {
            $this->bot->sendChatAction(
                chat_id: $recipient,
                action: 'typing'
            );

            Log::info('Telegram: Typing indicator sent', [
                'message_id' => $messageId,
                'chat_id' => $recipient,
            ]);
        } catch (\Exception $e) {
            Log::warning('Telegram: Failed to send typing indicator - '.$e->getMessage(), [
                'message_id' => $messageId,
                'chat_id' => $recipient,
            ]);
            // Don't throw - typing indicators are not critical
        }
    }

    public function sendMessage(string $to, string $text): void
    {
        try {
            $this->bot->sendMessage(
                text: $text,
                chat_id: $to,
                parse_mode: ParseMode::MARKDOWN
            );
        } catch (\Exception $e) {
            Log::error('Telegram sendMessage error: '.$e->getMessage(), [
                'chat_id' => $to,
                'text' => $text,
            ]);
            throw $e;
        }
    }

    public function sendWelcomeMenu(string $to): void
    {
        $text = "*Welcome! 👋*\n\n";
        $text .= "We are an internet shop which provides items.\n\n";
        $text .= "*Available Commands:*\n";
        $text .= "/catalog - Browse product groups\n";
        $text .= "/groups - View all product groups\n";
        $text .= "/items - View all available items\n";
        $text .= "/items {slug} - Items by group\n";
        $text .= "/item {slug} - Item details\n";

        $this->sendMessage($to, $text);
    }

    public function sendCatalog(string $to): void
    {
        $groups = $this->catalogService->listGroups();
        $text = "*Welcome to the Catalog!*\n\n";
        $text .= "Browse our product groups:\n\n";

        foreach ($groups as $group) {
            $text .= "• `/items {$group->slug}` - {$group->title}\n";
        }

        $text .= "\n*Available Commands:*\n";
        $text .= "/catalog - Show this catalog\n";
        $text .= "/groups - List all groups\n";
        $text .= "/items - List all items\n";
        $text .= "/items {slug} - Items by group\n";
        $text .= "/item {slug} - Item details\n";

        $this->sendMessage($to, $text);
    }

    public function sendGroups(string $to, int $page = 1): void
    {
        $groups = $this->catalogService->listGroups();
        $text = "*Available Product Groups:*\n\n";

        foreach ($groups as $group) {
            $text .= "*{$group->title}*\n";
            $text .= "Slug: `{$group->slug}`\n";
            if ($group->description) {
                $text .= "{$group->description}\n";
            }
            $text .= "\n";
        }

        $this->sendMessage($to, $text);
    }

    public function sendItems(string $to, ?string $groupSlug = null, int $page = 1): void
    {
        $items = $this->catalogService->listItems($groupSlug);
        $groupName = $groupSlug ? " in group '{$groupSlug}'" : '';

        if ($items->isEmpty()) {
            $this->sendMessage($to, "No items found{$groupName}.");

            return;
        }

        $text = "*Available Items{$groupName}:*\n\n";

        foreach ($items as $item) {
            $text .= "*{$item->title}*\n";
            $text .= "Price: \${$item->price}\n";
            $text .= "View: `/item {$item->slug}`\n\n";
        }

        $this->sendMessage($to, $text);
    }

    public function sendItemDetails(string $to, Item $item): void
    {
        $groupTitle = $item->group ? $item->group->title : 'Uncategorized';

        $caption = "*{$item->title}*\n\n";
        $caption .= "*Group:* {$groupTitle}\n";
        $caption .= "*Price:* \${$item->price}\n\n";
        $caption .= "*Description:*\n{$item->description}";

        try {
            // Send photo with caption if image exists
            if ($item->image && filter_var($item->image, FILTER_VALIDATE_URL)) {
                $this->bot->sendPhoto(
                    photo: $item->image,
                    chat_id: $to,
                    caption: $caption,
                    parse_mode: ParseMode::MARKDOWN
                );
            } else {
                // Fallback to text message if no valid image
                $this->sendMessage($to, $caption);
            }
        } catch (\Exception $e) {
            Log::error('Telegram sendItemDetails error: '.$e->getMessage(), [
                'chat_id' => $to,
                'item_id' => $item->id,
            ]);
            // Fallback to text message on error
            $this->sendMessage($to, $caption);
        }
    }
}
