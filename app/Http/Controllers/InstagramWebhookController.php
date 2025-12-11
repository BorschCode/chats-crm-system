<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;
use App\Services\InstagramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstagramWebhookController extends Controller
{
    protected InstagramService $instagramService;

    protected CatalogService $catalogService;

    public function __construct(InstagramService $instagramService, CatalogService $catalogService)
    {
        $this->instagramService = $instagramService;
        $this->catalogService = $catalogService;
    }

    public function handle(Request $request)
    {
        // NOTE: Instagram Messaging API webhooks are similar to WhatsApp's.
        // This is a simplified handler that assumes a text message is being sent.

        $text = strtolower(trim($request->input('text', '')));
        $from = $request->input('from', 'instagram_user_id'); // Placeholder for user ID

        if (empty($text)) {
            return response()->json(['status' => 'ok']);
        }

        Log::info("Instagram Webhook received message from {$from}: '{$text}'");

        // Command parsing
        $parts = explode(' ', $text);
        $command = $parts[0];
        $argument = $parts[1] ?? null;

        try {
            switch ($command) {
                case 'catalog':
                    $this->instagramService->sendCatalog($from);
                    break;
                case 'groups':
                    $this->instagramService->sendGroups($from);
                    break;
                case 'items':
                    $groupSlug = $argument;
                    $this->instagramService->sendItems($from, $groupSlug);
                    break;
                case 'item':
                    $itemSlug = $argument;
                    $item = $this->catalogService->getItem($itemSlug);
                    if ($item) {
                        $this->instagramService->sendItemDetails($from, $item);
                    } else {
                        $this->instagramService->sendMessage($from, "Item '{$itemSlug}' not found.");
                    }
                    break;
                default:
                    $this->instagramService->sendMessage($from, "Unknown command. Try 'catalog', 'groups', or 'items'.");
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Instagram Webhook Error: '.$e->getMessage());
            $this->instagramService->sendMessage($from, 'An error occurred while processing your request.');
        }

        return response()->json(['status' => 'ok']);
    }
}
