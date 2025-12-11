<?php

namespace App\Services;

use App\Models\Item;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class WhatsAppService implements MessagingService
{
    protected CatalogService $catalogService;
    protected Client $client;
    protected string $phoneNumberId;
    protected string $accessToken;
    protected string $apiVersion;
    protected string $apiBaseUrl;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;

        $config = config('services.whatsapp');
        $this->phoneNumberId = $config['phone_number_id'];
        $this->accessToken = $config['access_token'];
        $this->apiVersion = $config['api_version'];
        $this->apiBaseUrl = $config['api_base_url'];

        $this->client = new Client([
            'base_uri' => "{$this->apiBaseUrl}/{$this->apiVersion}/",
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    public function sendMessage(string $to, string $text): void
    {
        try {
            $response = $this->client->post("{$this->phoneNumberId}/messages", [
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $to,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => false,
                        'body' => $text,
                    ],
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            Log::info("WhatsApp message sent successfully", [
                'to' => $to,
                'message_id' => $body['messages'][0]['id'] ?? null,
            ]);
        } catch (GuzzleException $e) {
            Log::error("WhatsApp sendMessage error: " . $e->getMessage(), [
                'to' => $to,
                'text' => $text,
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);
            throw $e;
        }
    }

    public function sendCatalog(string $to): void
    {
        $groups = $this->catalogService->listGroups();
        $text = "*Welcome to the Catalog!*\n\n";
        $text .= "Browse our product groups:\n\n";

        foreach ($groups as $group) {
            $text .= "📦 items {$group->slug} - {$group->title}\n";
        }

        $text .= "\n*Available Commands:*\n";
        $text .= "• catalog - Show this catalog\n";
        $text .= "• groups - List all groups\n";
        $text .= "• items - List all items\n";
        $text .= "• items {slug} - Items by group\n";
        $text .= "• item {slug} - Item details\n";

        $this->sendMessage($to, $text);
    }

    public function sendGroups(string $to): void
    {
        $groups = $this->catalogService->listGroups();
        $text = "*Available Product Groups:*\n\n";

        foreach ($groups as $group) {
            $text .= "📁 *{$group->title}*\n";
            $text .= "   Slug: {$group->slug}\n";
            if ($group->description) {
                $text .= "   {$group->description}\n";
            }
            $text .= "\n";
        }

        $this->sendMessage($to, $text);
    }

    public function sendItems(string $to, ?string $groupSlug = null): void
    {
        $items = $this->catalogService->listItems($groupSlug);
        $groupName = $groupSlug ? " in group '{$groupSlug}'" : "";

        if ($items->isEmpty()) {
            $this->sendMessage($to, "No items found{$groupName}.");
            return;
        }

        $text = "*Available Items{$groupName}:*\n\n";

        foreach ($items as $item) {
            $text .= "🛍️ *{$item->title}*\n";
            $text .= "   Price: \${$item->price}\n";
            $text .= "   View: item {$item->slug}\n\n";
        }

        $this->sendMessage($to, $text);
    }

    public function sendItemDetails(string $to, Item $item): void
    {
        $groupTitle = $item->group ? $item->group->title : 'Uncategorized';

        // Check if item has a valid image URL
        if ($item->image && filter_var($item->image, FILTER_VALIDATE_URL)) {
            $this->sendImageWithCaption($to, $item, $groupTitle);
        } else {
            // Send as text message if no image
            $text = "*{$item->title}*\n\n";
            $text .= "*Group:* {$groupTitle}\n";
            $text .= "*Price:* \${$item->price}\n\n";
            $text .= "*Description:*\n{$item->description}";

            $this->sendMessage($to, $text);
        }
    }

    protected function sendImageWithCaption(string $to, Item $item, string $groupTitle): void
    {
        $caption = "*{$item->title}*\n\n";
        $caption .= "*Group:* {$groupTitle}\n";
        $caption .= "*Price:* \${$item->price}\n\n";
        $caption .= "*Description:*\n{$item->description}";

        try {
            $response = $this->client->post("{$this->phoneNumberId}/messages", [
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $to,
                    'type' => 'image',
                    'image' => [
                        'link' => $item->image,
                        'caption' => $caption,
                    ],
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            Log::info("WhatsApp image sent successfully", [
                'to' => $to,
                'message_id' => $body['messages'][0]['id'] ?? null,
            ]);
        } catch (GuzzleException $e) {
            Log::error("WhatsApp sendImageWithCaption error: " . $e->getMessage(), [
                'to' => $to,
                'item_id' => $item->id,
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);

            // Fallback to text message
            $text = "*{$item->title}*\n\n";
            $text .= "*Group:* {$groupTitle}\n";
            $text .= "*Price:* \${$item->price}\n\n";
            $text .= "*Description:*\n{$item->description}\n\n";
            $text .= "*Image:* {$item->image}";

            $this->sendMessage($to, $text);
        }
    }
}
