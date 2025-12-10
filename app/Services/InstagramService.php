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

    public function sendMessage(string $to, string $text): void
    {
        // In a real application, this would use the Instagram Messaging API to send a message.
        // For this task, we will log the action.
        Log::info("Instagram: Sending message to {$to}: '{$text}'");
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

    public function sendGroups(string $to): void
    {
        $groups = $this->catalogService->listGroups();
        $text = "Available Groups:\n\n";
        foreach ($groups as $group) {
            $text .= "{$group->title} (Slug: {$group->slug})\n";
        }
        $this->sendMessage($to, $text);
    }

    public function sendItems(string $to, ?string $groupSlug = null): void
    {
        $items = $this->catalogService->listItems($groupSlug);
        $groupName = $groupSlug ? " in '{$groupSlug}'" : "";
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
