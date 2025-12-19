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

    /**
     * Handle webhook verification (GET request from Meta)
     */
    public function verify(Request $request)
    {
        Log::info('WhatsApp webhook verification request received', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'query_params' => $request->query(),
        ]);

        // Facebook sends: hub.mode, hub.challenge, hub.verify_token
        // PHP converts dots to underscores in query params
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $verifyToken = config('services.whatsapp.webhook_verify_token');

        Log::info('WhatsApp webhook verification details', [
            'received_mode' => $mode,
            'received_token' => $token,
            'expected_token' => $verifyToken,
            'challenge' => $challenge,
            'tokens_match' => $token === $verifyToken,
        ]);

        if ($mode === 'subscribe' && $token === $verifyToken) {
            Log::info('WEBHOOK VERIFIED');

            // Return ONLY the challenge as plain text, exactly like Node.js version
            return response($challenge, 200)
                ->header('Content-Type', 'text/plain');
        }

        Log::warning('WhatsApp webhook verification failed', [
            'mode' => $mode,
            'received_token' => $token,
            'expected_token' => $verifyToken,
        ]);

        return response('', 403);
    }

    /**
     * Handle incoming messages (POST request from Meta)
     */
    public function handle(Request $request)
    {
        try {
            $timestamp = now()->format('Y-m-d H:i:s');
            Log::info("\n\nWebhook received {$timestamp}\n");

            $payload = $request->all();
            Log::info(json_encode($payload, JSON_PRETTY_PRINT));

            // WhatsApp Cloud API structure: entry -> changes -> value -> messages
            if (! isset($payload['entry'])) {
                return response('', 200);
            }

            foreach ($payload['entry'] as $entry) {
                if (! isset($entry['changes'])) {
                    continue;
                }

                foreach ($entry['changes'] as $change) {
                    if (! isset($change['value']['messages'])) {
                        continue;
                    }

                    $value = $change['value'];
                    $messages = $value['messages'];

                    foreach ($messages as $message) {
                        $this->processMessage($message);
                    }
                }
            }

            return response('', 200);
        } catch (\Exception $e) {
            Log::error('WhatsApp webhook error: '.$e->getMessage(), [
                'exception' => $e,
                'payload' => $request->all(),
            ]);

            return response('', 500);
        }
    }

    protected function processMessage(array $message): void
    {
        $from = $message['from'];
        $messageId = $message['id'];
        $timestamp = $message['timestamp'];

        // Only process text messages
        if ($message['type'] !== 'text') {
            Log::info('Ignoring non-text message', ['type' => $message['type']]);

            return;
        }

        $text = strtolower(trim($message['text']['body']));

        Log::info('Processing WhatsApp message', [
            'from' => $from,
            'message_id' => $messageId,
            'text' => $text,
        ]);

        // Parse command
        $parts = explode(' ', $text, 2);
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
                    if (! $argument) {
                        $this->whatsAppService->sendMessage($from, 'Please provide an item slug. Example: item product-name');
                        break;
                    }

                    $itemSlug = $argument;
                    $item = $this->catalogService->getItem($itemSlug);

                    if ($item) {
                        $this->whatsAppService->sendItemDetails($from, $item);
                    } else {
                        $this->whatsAppService->sendMessage($from, "Item '{$itemSlug}' not found.");
                    }
                    break;

                default:
                    $this->whatsAppService->sendMessage(
                        $from,
                        "Unknown command. Available commands:\n• catalog\n• groups\n• items\n• item {slug}"
                    );
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Error processing WhatsApp command', [
                'from' => $from,
                'command' => $command,
                'exception' => $e->getMessage(),
            ]);

            // Try to send error message, but don't fail if it doesn't work
            try {
                $this->whatsAppService->sendMessage(
                    $from,
                    'An error occurred while processing your request. Please try again later.'
                );
            } catch (\Exception $sendError) {
                // Log but don't throw - webhook was processed successfully
                Log::warning('Could not send error message to user', [
                    'from' => $from,
                    'error' => $sendError->getMessage(),
                ]);
            }
        }
    }
}
