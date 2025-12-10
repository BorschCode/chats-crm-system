<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    protected WhatsAppService $whatsAppService;
    protected CatalogService $catalogService;

    public function __construct(WhatsAppService $whatsAppService, CatalogService $catalogService)
    {
        $this->whatsAppService = $whatsAppService;
        $this->catalogService = $catalogService;
    }

    public function handle(Request $request)
    {
        // NOTE: WhatsApp Cloud API webhooks are complex. This is a simplified handler
        // that assumes a text message is being sent and extracts the 'from' number and 'text' content.
        // The actual implementation would involve parsing the 'entry' and 'changes' array.

        // For simplicity, we'll assume the message text is directly in the request for demonstration
        // In a real scenario, you'd need to parse the WhatsApp payload structure.
        $text = strtolower(trim($request->input('text', '')));
        $from = $request->input('from', 'whatsapp_user_id'); // Placeholder for user ID

        if (empty($text)) {
            return response()->json(['status' => 'ok']);
        }

        Log::info("WhatsApp Webhook received message from {$from}: '{$text}'");

        // Command parsing
        $parts = explode(' ', $text);
        $command = $parts[0];
        $argument = $parts[1] ?? null;

        try {
            switch ($command) {
                case 'catalog':
                    $this->whatsAppService->sendCatalog($from);
                    break;
                case 'groups':
                    $this->whatsAppService->sendGroups($from);
                    break;
                case 'items':
                    $groupSlug = $argument;
                    $this->whatsAppService->sendItems($from, $groupSlug);
                    break;
                case 'item':
                    $itemSlug = $argument;
                    $item = $this->catalogService->getItem($itemSlug);
                    if ($item) {
                        $this->whatsAppService->sendItemDetails($from, $item);
                    } else {
                        $this->whatsAppService->sendMessage($from, "Item '{$itemSlug}' not found.");
                    }
                    break;
                default:
                    $this->whatsAppService->sendMessage($from, "Unknown command. Try 'catalog', 'groups', or 'items'.");
                    break;
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp Webhook Error: " . $e->getMessage());
            $this->whatsAppService->sendMessage($from, "An error occurred while processing your request.");
        }

        return response()->json(['status' => 'ok']);
    }
}
