<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Facades\Log;

class InstagramService implements MessagingService
{
    protected CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function markReadAndSendTypingIndicator(string $messageId, string $recipient): void
    {
        // In a real application, this would use the Instagram Messaging API
        // to mark message as read and send a typing indicator
        Log::info('Instagram: Mark as read and typing indicator', [
            'message_id' => $messageId,
            'recipient' => $recipient,
        ]);
    }

    public function sendMessage(string $to, string $text): void
    {
        // In a real application, this would use the Instagram Messaging API to send a message.
        // For this task, we will log the action.
        Log::info("Instagram: Sending message to {$to}: '{$text}'");
    }

    public function sendWelcomeMenu(string $to): void
    {
        $text = "Welcome! 👋\n\n";
        $text .= "We are an internet shop which provides items.\n\n";
        $text .= "Available commands:\n";
        $text .= "• catalog - Browse product groups\n";
        $text .= "• groups - View all product groups\n";
        $text .= "• items - View all available items\n";
        $text .= "• item <slug> - Item details\n";

        $this->sendMessage($to, $text);
    }

    public function sendCatalog(string $to): void
    {
        $groups = $this->catalogService->listGroups();
        $text = "Welcome to the Catalog! Reply with one of the following commands:\n\n";
        foreach ($groups as $group) {
            $text .= "items {$group->slug} - {$group->title}\n";
        }
        $text .= "\nOther commands: groups, items, item <slug>";
        $this->sendMessage($to, $text);
    }

    public function sendGroups(string $to, int $page = 1): void
    {
        $groups = $this->catalogService->listGroups();
        $text = "Available Groups:\n\n";
        foreach ($groups as $group) {
            $text .= "{$group->title} (Slug: {$group->slug})\n";
        }
        $this->sendMessage($to, $text);
    }

    public function sendItems(string $to, ?string $groupSlug = null, int $page = 1): void
    {
        $items = $this->catalogService->listItems($groupSlug);
        $groupName = $groupSlug ? " in '{$groupSlug}'" : '';
        $text = "Available Items{$groupName}:\n\n";

        if ($items->isEmpty()) {
            $text = "No items found{$groupName}.";
        } else {
            foreach ($items as $item) {
                $text .= "item {$item->slug} - {$item->title} ({$item->price} USD)\n";
            }
        }
        $this->sendMessage($to, $text);
    }

    public function sendItemDetails(string $to, Item $item): void
    {
        $groupTitle = $item->group ? $item->group->title : 'Uncategorized';
        $text = "Item Details:\n\n";
        $text .= "Title: {$item->title}\n";
        $text .= "Group: {$groupTitle}\n";
        $text .= "Price: {$item->price} USD\n";
        $text .= "Description: {$item->description}\n";
        $text .= "Image: {$item->image}\n";
        $this->sendMessage($to, $text);
    }
}
