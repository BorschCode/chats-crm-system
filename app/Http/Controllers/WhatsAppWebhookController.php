<?php

namespace App\Http\Controllers;

use App\Enums\WhatsAppCommand;
use App\Services\CatalogService;
use App\Services\WhatsAppService;
use App\Services\WhatsAppWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class WhatsAppWebhookController extends Controller
{
    public function __construct(
        protected WhatsAppService $whatsAppService,
        protected CatalogService $catalogService,
        protected WhatsAppWebhookService $webhookService
    ) {}

    /**
     * Handle webhook verification (GET request from Meta)
     */
    public function verify(Request $request)
    {
        Log::info('WhatsApp webhook verification request received', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'query_params' => $request->query(),
        ]);

        // Facebook sends: hub.mode, hub.challenge, hub.verify_token
        // PHP converts dots to underscores in query params
        $mode = $request->query('hub_mode', '');
        $token = $request->query('hub_verify_token', '');
        $challenge = $request->query('hub_challenge', '');

        $verifiedChallenge = $this->webhookService->verifyWebhook($mode, $token, $challenge);

        if ($verifiedChallenge !== null) {
            return response($verifiedChallenge, Response::HTTP_OK)
                ->header('Content-Type', 'text/plain');
        }

        return response('', Response::HTTP_FORBIDDEN);
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
                return response('', Response::HTTP_OK);
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

            return response('', Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('WhatsApp webhook error: '.$e->getMessage(), [
                'exception' => $e,
                'payload' => $request->all(),
            ]);

            return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    protected function processMessage(array $message): void
    {
        $from = $message['from'];
        $messageId = $message['id'];
        $messageType = $message['type'];

        // Mark message as read
        $this->whatsAppService->markReadAndSendTypingIndicator($messageId);

        // Handle interactive list responses
        if ($messageType === 'interactive') {
            $this->processInteractiveMessage($message, $from);

            return;
        }

        // Only process text messages
        if ($messageType !== 'text') {
            Log::info('Ignoring non-text/non-interactive message', ['type' => $messageType]);

            return;
        }

        $text = strtolower(trim($message['text']['body']));

        Log::info('Processing WhatsApp message', [
            'message_id' => $messageId,
            'text' => $text,
            'message' => $message,
        ]);

        // Show typing indicator while processing (also marks message as read)
        $this->whatsAppService->markReadAndSendTypingIndicator($messageId);

        // Parse command
        $parts = explode(' ', $text, 2);
        $commandText = $parts[0];
        $argument = $parts[1] ?? null;

        $command = WhatsAppCommand::fromText($commandText);

        try {
            match ($command) {
                WhatsAppCommand::Catalog => $this->whatsAppService->sendCatalog($from),
                WhatsAppCommand::Groups => $this->whatsAppService->sendGroups($from),
                WhatsAppCommand::Items => $this->whatsAppService->sendItems($from, $argument),
                WhatsAppCommand::Item => $this->handleItemCommand($from, $argument),
                default => $this->whatsAppService->sendWelcomeMenu($from),
            };
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

    protected function handleItemCommand(string $from, ?string $argument): void
    {
        if (! $argument) {
            $this->whatsAppService->sendMessage($from, 'Please provide an item slug. Example: item product-name');

            return;
        }

        $item = $this->catalogService->getItem($argument);

        if ($item) {
            $this->whatsAppService->sendItemDetails($from, $item);
        } else {
            $this->whatsAppService->sendMessage($from, "Item '{$argument}' not found.");
        }
    }

    protected function processInteractiveMessage(array $message, string $from): void
    {
        $messageId = $message['id'];
        $interactive = $message['interactive'] ?? [];
        $type = $interactive['type'] ?? null;

        Log::info('Processing WhatsApp interactive message', [
            'message data' => $message,
        ]);

        // Show typing indicator while processing (also marks message as read)
        $this->whatsAppService->markReadAndSendTypingIndicator($messageId);

        // Handle list reply
        if ($type === 'list_reply') {
            $selectedId = $interactive['list_reply']['id'] ?? null;

            if (! $selectedId) {
                Log::warning('Interactive list reply received without ID', ['from' => $from]);

                return;
            }

            // Parse the selected ID (format: "menu_{action}" or "group_{slug}")
            if (str_starts_with($selectedId, 'menu_') || $selectedId === 'back_to_menu') {
                // Handle welcome menu selections
                $command = WhatsAppCommand::fromMenuId($selectedId);

                Log::info('User selected from menu', [
                    'from' => $from,
                    'selected_id' => $selectedId,
                    'command' => $command?->value,
                ]);

                try {
                    match ($command) {
                        WhatsAppCommand::Catalog => $this->whatsAppService->sendCatalog($from),
                        WhatsAppCommand::Groups => $this->whatsAppService->sendGroups($from),
                        WhatsAppCommand::Items => $this->whatsAppService->sendItems($from),
                        WhatsAppCommand::BackToMenu => $this->whatsAppService->sendWelcomeMenu($from),
                        default => $this->whatsAppService->sendWelcomeMenu($from),
                    };
                } catch (\Exception $e) {
                    Log::error('Error processing menu selection', [
                        'from' => $from,
                        'selected_id' => $selectedId,
                        'exception' => $e->getMessage(),
                    ]);

                    try {
                        $this->whatsAppService->sendMessage(
                            $from,
                            'An error occurred. Please try again later.'
                        );
                    } catch (\Exception $sendError) {
                        Log::warning('Could not send error message to user', [
                            'from' => $from,
                            'error' => $sendError->getMessage(),
                        ]);
                    }
                }
            } elseif (str_starts_with($selectedId, 'group_')) {
                // Handle group selections from catalog
                $groupSlug = substr($selectedId, 6); // Remove "group_" prefix
                Log::info('User selected group from interactive list', [
                    'from' => $from,
                    'group_slug' => $groupSlug,
                ]);

                try {
                    $this->whatsAppService->sendItems($from, $groupSlug);
                } catch (\Exception $e) {
                    Log::error('Error sending items for selected group', [
                        'from' => $from,
                        'group_slug' => $groupSlug,
                        'exception' => $e->getMessage(),
                    ]);

                    try {
                        $this->whatsAppService->sendMessage(
                            $from,
                            'An error occurred while loading items. Please try again later.'
                        );
                    } catch (\Exception $sendError) {
                        Log::warning('Could not send error message to user', [
                            'from' => $from,
                            'error' => $sendError->getMessage(),
                        ]);
                    }
                }
            } elseif (str_starts_with($selectedId, 'item_')) {
                // Handle item selections from interactive list
                $itemSlug = substr($selectedId, 5); // Remove "item_" prefix
                Log::info('User selected item from interactive list', [
                    'from' => $from,
                    'item_slug' => $itemSlug,
                ]);

                try {
                    $item = $this->catalogService->getItem($itemSlug);

                    if ($item) {
                        $this->whatsAppService->sendItemDetails($from, $item);
                    } else {
                        $this->whatsAppService->sendMessage(
                            $from,
                            "Item '{$itemSlug}' not found."
                        );
                    }
                } catch (\Exception $e) {
                    Log::error('Error sending item details', [
                        'from' => $from,
                        'item_slug' => $itemSlug,
                        'exception' => $e->getMessage(),
                    ]);

                    try {
                        $this->whatsAppService->sendMessage(
                            $from,
                            'An error occurred while loading item details. Please try again later.'
                        );
                    } catch (\Exception $sendError) {
                        Log::warning('Could not send error message to user', [
                            'from' => $from,
                            'error' => $sendError->getMessage(),
                        ]);
                    }
                }
            } elseif (str_starts_with($selectedId, 'next_page_')) {
                // Handle pagination - format: next_page_{groupSlug}_{pageNumber}
                $parts = explode('_', $selectedId, 4); // Split into: ['next', 'page', groupSlug, pageNumber]

                if (count($parts) >= 4) {
                    $groupSlug = $parts[2]; // Extract group slug or 'all'
                    $page = (int) $parts[3]; // Extract page number

                    Log::info('User requested next page of items', [
                        'from' => $from,
                        'group_slug' => $groupSlug,
                        'page' => $page,
                    ]);

                    try {
                        // If groupSlug is 'all', send all items (no filter)
                        $groupFilter = ($groupSlug === 'all') ? null : $groupSlug;
                        $this->whatsAppService->sendItems($from, $groupFilter, $page);
                    } catch (\Exception $e) {
                        Log::error('Error sending next page of items', [
                            'from' => $from,
                            'group_slug' => $groupSlug,
                            'page' => $page,
                            'exception' => $e->getMessage(),
                        ]);

                        try {
                            $this->whatsAppService->sendMessage(
                                $from,
                                'An error occurred while loading more items. Please try again later.'
                            );
                        } catch (\Exception $sendError) {
                            Log::warning('Could not send error message to user', [
                                'from' => $from,
                                'error' => $sendError->getMessage(),
                            ]);
                        }
                    }
                } else {
                    Log::warning('Invalid next_page ID format', [
                        'from' => $from,
                        'selected_id' => $selectedId,
                    ]);
                }
            } elseif (str_starts_with($selectedId, 'next_groups_page_')) {
                // Handle group pagination - format: next_groups_page_{pageNumber}
                $pageNumber = (int) Str::after($selectedId, 'next_groups_page_');

                Log::info('User requested next page of groups', [
                    'from' => $from,
                    'page' => $pageNumber,
                ]);

                try {
                    $this->whatsAppService->sendGroups($from, $pageNumber);
                } catch (\Exception $e) {
                    Log::error('Error sending next page of groups', [
                        'from' => $from,
                        'page' => $pageNumber,
                        'exception' => $e->getMessage(),
                    ]);

                    try {
                        $this->whatsAppService->sendMessage(
                            $from,
                            'An error occurred while loading more groups. Please try again later.'
                        );
                    } catch (\Exception $sendError) {
                        Log::warning('Could not send error message to user', [
                            'from' => $from,
                            'error' => $sendError->getMessage(),
                        ]);
                    }
                }
            } else {
                Log::warning('Unknown interactive list ID format', [
                    'from' => $from,
                    'selected_id' => $selectedId,
                ]);
            }
        } elseif ($type === 'button_reply') {
            // Handle button replies (navigation buttons)
            $buttonId = $interactive['button_reply']['id'] ?? null;

            if (! $buttonId) {
                Log::warning('Button reply received without ID', ['from' => $from]);

                return;
            }

            Log::info('Button reply received', [
                'from' => $from,
                'button_id' => $buttonId,
            ]);

            // Handle navigation buttons
            if ($buttonId === WhatsAppCommand::BackToMenu->value) {
                // User clicked "Main Menu" button
                try {
                    $this->whatsAppService->sendWelcomeMenu($from);
                } catch (\Exception $e) {
                    Log::error('Error sending welcome menu', [
                        'from' => $from,
                        'exception' => $e->getMessage(),
                    ]);
                }
            } elseif (str_starts_with($buttonId, 'back_to_list_')) {
                // User clicked "Back to List" button - format: back_to_list_{groupSlug}
                $groupSlug = Str::after($buttonId, 'back_to_list_');

                Log::info('User navigating back to item list', [
                    'from' => $from,
                    'group_slug' => $groupSlug,
                ]);

                try {
                    $this->whatsAppService->sendItems($from, $groupSlug);
                } catch (\Exception $e) {
                    Log::error('Error sending items list', [
                        'from' => $from,
                        'group_slug' => $groupSlug,
                        'exception' => $e->getMessage(),
                    ]);

                    try {
                        $this->whatsAppService->sendMessage(
                            $from,
                            'An error occurred. Please try again later.'
                        );
                    } catch (\Exception $sendError) {
                        Log::warning('Could not send error message to user', [
                            'from' => $from,
                            'error' => $sendError->getMessage(),
                        ]);
                    }
                }
            } else {
                Log::warning('Unknown button ID', [
                    'from' => $from,
                    'button_id' => $buttonId,
                ]);
            }
        } else {
            Log::warning('Unknown interactive type', [
                'from' => $from,
                'type' => $type,
            ]);
        }
    }
}
