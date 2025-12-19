<?php

namespace App\Services;

use App\Models\Item;

interface MessagingService
{
    public function sendMessage(string $to, string $text): void;

    public function sendWelcomeMenu(string $to): void;

    public function sendCatalog(string $to): void;

    public function sendGroups(string $to): void;

    public function sendItems(string $to, ?string $groupSlug = null): void;

    public function sendItemDetails(string $to, Item $item): void;
}
