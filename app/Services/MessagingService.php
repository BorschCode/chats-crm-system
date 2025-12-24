<?php

namespace App\Services;

use App\Models\Item;

interface MessagingService
{
    /**
     * Mark message as read and/or send typing indicator
     *
     * @param  string  $messageId  The message ID to mark as read (WhatsApp) or for reference
     * @param  string  $recipient  The chat/recipient ID to send typing indicator to (Telegram)
     */
    public function markReadAndSendTypingIndicator(string $messageId, string $recipient): void;

    public function sendMessage(string $to, string $text): void;

    public function sendWelcomeMenu(string $to): void;

    public function sendCatalog(string $to): void;

    public function sendGroups(string $to, int $page = 1): void;

    public function sendItems(string $to, ?string $groupSlug = null, int $page = 1): void;

    public function sendItemDetails(string $to, Item $item): void;
}
