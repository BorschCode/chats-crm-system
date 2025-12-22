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

    public function __construct(
        CatalogService $catalogService,
        array $config
    ) {
        $this->catalogService = $catalogService;
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
        ]);
    }

    /** @deprecated use markReadAndSendTypingIndicator */
    public function markAsRead(string $messageId): void
    {
        try {
            $response = $this->client->post("{$this->phoneNumberId}/messages", [
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'status' => 'read',
                    'message_id' => $messageId,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            Log::info('WhatsApp typing indicator sent (message marked as read)', [
                'message_id' => $messageId,
                'response' => $body,  // This will log the actual JSON response
            ]);
        } catch (GuzzleException $e) {
            Log::warning('WhatsApp markAsRead error: '.$e->getMessage(), [
                'message_id' => $messageId,
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);
            // Don't throw - this is not critical
        }
    }

    public function markReadAndSendTypingIndicator(string $messageId): void
    {
        try {
            $response = $this->client->post("{$this->phoneNumberId}/messages", [
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'status' => 'read',
                    'message_id' => $messageId,
                    'typing_indicator' => [
                        'type' => 'text',
                    ],
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            Log::info('WhatsApp typing indicator sent (message marked as read)', [
                'message_id' => $messageId,
                'response' => $body,  // This will log the actual JSON response
            ]);

        } catch (GuzzleException $exception) {
            Log::warning('WhatsApp typing indicator error: '.$exception->getMessage(), [
                'message_id' => $messageId,
                'response' => $exception->hasResponse() ? $exception->getResponse()->getBody()->getContents() : null,
            ]);
            // Don't throw - typing indicators are not critical
        }
    }

    public function sendInteractiveList(string $to, array $listData): void
    {
        try {
            $payload = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $to,
                'type' => 'interactive',
                'interactive' => [
                    'type' => 'list',
                    'body' => [
                        'text' => $listData['body'],
                    ],
                    'action' => [
                        'button' => $listData['button'],
                        'sections' => $listData['sections'],
                    ],
                ],
            ];

            // Add optional header if provided
            if (isset($listData['header'])) {
                $payload['interactive']['header'] = [
                    'type' => 'text',
                    'text' => $listData['header'],
                ];
            }

            // Add optional footer if provided
            if (isset($listData['footer'])) {
                $payload['interactive']['footer'] = [
                    'text' => $listData['footer'],
                ];
            }

            $response = $this->client->post("{$this->phoneNumberId}/messages", [
                'json' => $payload,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            Log::info('WhatsApp interactive list sent successfully', [
                'to' => $to,
                'message_id' => $body['messages'][0]['id'] ?? null,
            ]);
        } catch (GuzzleException $e) {
            $errorDetails = [
                'to' => $to,
                'list_data' => $listData,
            ];

            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                $errorDetails['response'] = $responseBody;

                Log::error('WhatsApp sendInteractiveList error: '.$e->getMessage(), $errorDetails);
            } else {
                Log::error('WhatsApp sendInteractiveList error: '.$e->getMessage(), $errorDetails);
            }

            throw $e;
        }
    }

    public function sendInteractiveButtons(string $to, array $buttonData): void
    {
        try {
            $payload = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $to,
                'type' => 'interactive',
                'interactive' => [
                    'type' => 'button',
                    'body' => [
                        'text' => $buttonData['body'],
                    ],
                    'action' => [
                        'buttons' => $buttonData['buttons'],
                    ],
                ],
            ];

            // Add optional header if provided
            if (isset($buttonData['header'])) {
                $payload['interactive']['header'] = [
                    'type' => 'text',
                    'text' => $buttonData['header'],
                ];
            }

            // Add optional footer if provided
            if (isset($buttonData['footer'])) {
                $payload['interactive']['footer'] = [
                    'text' => $buttonData['footer'],
                ];
            }

            $response = $this->client->post("{$this->phoneNumberId}/messages", [
                'json' => $payload,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            Log::info('WhatsApp interactive buttons sent successfully', [
                'to' => $to,
                'message_id' => $body['messages'][0]['id'] ?? null,
            ]);
        } catch (GuzzleException $exception) {
            $errorDetails = [
                'to' => $to,
                'button_data' => $buttonData,
            ];

            if ($exception->hasResponse()) {
                $responseBody = $exception->getResponse()->getBody()->getContents();
                $errorDetails['response'] = $responseBody;

                Log::error('WhatsApp sendInteractiveButtons error: '.$exception->getMessage(), $errorDetails);
            } else {
                Log::error('WhatsApp sendInteractiveButtons error: '.$exception->getMessage(), $errorDetails);
            }

            throw $exception;
        }
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
            Log::info('WhatsApp message sent successfully', [
                'to' => $to,
                'message_id' => $body['messages'][0]['id'] ?? null,
            ]);
        } catch (GuzzleException $e) {
            $errorDetails = [
                'to' => $to,
                'text' => $text,
            ];

            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                $errorDetails['response'] = $responseBody;

                // Parse Meta API error
                $errorData = json_decode($responseBody, true);
                if (isset($errorData['error']['code'])) {
                    $errorCode = $errorData['error']['code'];
                    $errorMessage = $errorData['error']['message'] ?? 'Unknown error';

                    // Special handling for common errors
                    if ($errorCode === 131030) {
                        Log::warning('WhatsApp: Recipient not in allowed list (test mode restriction)', [
                            'to' => $to,
                            'error_code' => $errorCode,
                            'hint' => 'Add this number to your WhatsApp Business API allowed list in Meta Developer Console',
                            'docs' => 'https://developers.facebook.com/docs/whatsapp/cloud-api/get-started#send-messages',
                        ]);
                    } elseif ($errorCode === 190) {
                        Log::error('WhatsApp: Access token expired or invalid', [
                            'error_code' => $errorCode,
                            'hint' => 'Generate a new access token in Meta Developer Console',
                        ]);
                    } elseif ($errorCode === 100 && str_contains($errorMessage, '4096')) {
                        Log::error('WhatsApp: Message too long (exceeds 4096 character limit)', [
                            'error_code' => $errorCode,
                            'message_length' => strlen($text),
                            'hint' => 'Message should be split into multiple messages with pagination',
                        ]);
                    } else {
                        Log::error('WhatsApp API error: '.$errorMessage, [
                            'error_code' => $errorCode,
                            'to' => $to,
                        ]);
                    }
                }
            } else {
                Log::error('WhatsApp sendMessage error: '.$e->getMessage(), $errorDetails);
            }

            throw $e;
        }
    }

    public function sendWelcomeMenu(string $to): void
    {
        $this->sendInteractiveList($to, [
            'header' => 'Welcome! 👋',
            'body' => "We are an internet shop which provides items.\n\nChoose an option below to explore our catalog:",
            'button' => 'Browse Menu',
            'sections' => [
                [
                    'title' => 'Main Menu',
                    'rows' => [
                        [
                            'id' => \App\Enums\WhatsAppCommand::MenuCatalog->value,
                            'title' => 'Catalog',
                            'description' => 'Browse product groups',
                        ],
                        [
                            'id' => \App\Enums\WhatsAppCommand::MenuGroups->value,
                            'title' => 'Groups',
                            'description' => 'View all product groups',
                        ],
                        [
                            'id' => \App\Enums\WhatsAppCommand::MenuItems->value,
                            'title' => 'Items',
                            'description' => 'View all available items',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function sendCatalog(string $to): void
    {
        $groups = $this->catalogService->listGroups();

        if ($groups->isEmpty()) {
            $this->sendMessage($to, 'No product groups available at the moment.');

            return;
        }

        // Interactive List Messages have a limit of 10 rows per section and 10 sections max
        // We'll use one section with up to 10 groups
        $rows = [];
        $count = 0;

        foreach ($groups as $group) {
            if ($count >= 10) {
                break; // WhatsApp limit: max 10 rows per section
            }

            $rows[] = [
                'id' => "group_{$group->slug}",
                'title' => substr($group->title, 0, 24), // Max 24 characters
                'description' => $group->description
                    ? substr($group->description, 0, 72) // Max 72 characters
                    : "View items in {$group->title}",
            ];
            $count++;
        }

        $this->sendInteractiveList($to, [
            'body' => "Welcome to our catalog! 🛍️\n\nBrowse our product groups and discover amazing items.",
            'button' => 'View Groups',
            'sections' => [
                [
                    'title' => 'Product Groups',
                    'rows' => $rows,
                ],
            ],
        ]);

        // If there are more than 10 groups, send remaining as text
        if ($groups->count() > 10) {
            $remainingText = "\n*Additional Groups:*\n";
            foreach ($groups->skip(10) as $group) {
                $remainingText .= "• items {$group->slug} - {$group->title}\n";
            }
            $this->sendMessage($to, $remainingText);
        }
    }

    public function sendGroups(string $to, int $page = 1): void
    {
        $groups = $this->catalogService->listGroups();

        if ($groups->isEmpty()) {
            $this->sendMessage($to, 'No product groups available at the moment.');

            return;
        }

        // Interactive List Messages have a limit of 10 rows per section
        // We'll use 9 rows for groups + 1 row for "Next Page" if needed
        $groupsPerPage = 9;
        $offset = ($page - 1) * $groupsPerPage;
        $totalGroups = $groups->count();
        $hasMoreGroups = $totalGroups > ($offset + $groupsPerPage);

        // Get groups for current page
        $pageGroups = $groups->skip($offset)->take($groupsPerPage);

        if ($pageGroups->isEmpty()) {
            $this->sendMessage($to, 'No more groups to display.');

            return;
        }

        $rows = [];

        foreach ($pageGroups as $group) {
            $rows[] = [
                'id' => "group_{$group->slug}",
                'title' => substr($group->title, 0, 24), // Max 24 characters
                'description' => $group->description
                    ? substr($group->description, 0, 72) // Max 72 characters
                    : "View items in {$group->title}",
            ];
        }

        // Add "Next Page" button if there are more groups
        if ($hasMoreGroups) {
            $nextPage = $page + 1;
            $rows[] = [
                'id' => "next_groups_page_{$nextPage}",
                'title' => '➡️ Next Page',
                'description' => "View more groups (Page {$nextPage})",
            ];
        }

        $pageInfo = $totalGroups > $groupsPerPage ? " (Page {$page})" : '';
        $bodyText = "Browse all product groups 📁{$pageInfo}\n\nTap a group to view items.";

        $this->sendInteractiveList($to, [
            'body' => $bodyText,
            'button' => 'View Groups',
            'sections' => [
                [
                    'title' => 'Product Groups',
                    'rows' => $rows,
                ],
            ],
        ]);
    }

    public function sendItems(string $to, ?string $groupSlug = null, int $page = 1): void
    {
        $items = $this->catalogService->listItems($groupSlug);
        $groupName = $groupSlug ? " in group '{$groupSlug}'" : '';

        if ($items->isEmpty()) {
            $this->sendMessage($to, "No items found{$groupName}.");

            return;
        }

        // Interactive List Messages have a limit of 10 rows per section
        // We'll use 9 rows for items + 1 row for "Next Page" if needed
        $itemsPerPage = 9;
        $offset = ($page - 1) * $itemsPerPage;
        $totalItems = $items->count();
        $hasMoreItems = $totalItems > ($offset + $itemsPerPage);

        // Get items for current page
        $pageItems = $items->skip($offset)->take($itemsPerPage);

        if ($pageItems->isEmpty()) {
            $this->sendMessage($to, 'No more items to display.');

            return;
        }

        $rows = [];

        foreach ($pageItems as $item) {
            $priceText = "\${$item->price}";
            $rows[] = [
                'id' => "item_{$item->slug}",
                'title' => substr($item->title, 0, 24), // Max 24 characters
                'description' => substr($priceText, 0, 72), // Max 72 characters
            ];
        }

        // Add "Next Page" button if there are more items
        if ($hasMoreItems) {
            $nextPage = $page + 1;
            $pageIdentifier = $groupSlug ?: 'all';
            $rows[] = [
                'id' => "next_page_{$pageIdentifier}_{$nextPage}",
                'title' => '➡️ Next Page',
                'description' => "View more items (Page {$nextPage})",
            ];
        }

        $pageInfo = $totalItems > $itemsPerPage ? " (Page {$page})" : '';
        $bodyText = $groupSlug
            ? "Browse items in '{$groupSlug}' 🛍️{$pageInfo}\n\nTap an item to view details."
            : "Browse all items 🛍️{$pageInfo}\n\nTap an item to view details.";

        $this->sendInteractiveList($to, [
            'body' => $bodyText,
            'button' => 'View Items',
            'sections' => [
                [
                    'title' => 'Available Items',
                    'rows' => $rows,
                ],
            ],
        ]);
    }

    public function sendItemDetails(string $to, Item $item): void
    {
        $groupTitle = $item->group ? $item->group->title : 'Uncategorized';
        $groupSlug = $item->group ? $item->group->slug : null;

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

        // Send navigation buttons
        $this->sendNavigationButtons($to, $groupSlug, $groupTitle);
    }

    protected function sendNavigationButtons(string $to, ?string $groupSlug, string $groupTitle): void
    {
        $buttons = [];

        // Add "Back to List" button if item belongs to a group
        if ($groupSlug) {
            $buttons[] = [
                'type' => 'reply',
                'reply' => [
                    'id' => "back_to_list_{$groupSlug}",
                    'title' => substr("⬅️ Back to {$groupTitle}", 0, 20), // Max 20 characters
                ],
            ];
        }

        // Add "Main Menu" button
        $buttons[] = [
            'type' => 'reply',
            'reply' => [
                'id' => \App\Enums\WhatsAppCommand::BackToMenu->value,
                'title' => '🏠 Main Menu',
            ],
        ];

        try {
            $this->sendInteractiveButtons($to, [
                'body' => 'What would you like to do next?',
                'buttons' => $buttons,
            ]);
        } catch (\Exception $e) {
            Log::warning('Could not send navigation buttons', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
            // Don't throw - navigation buttons are not critical
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
            Log::info('WhatsApp image sent successfully', [
                'to' => $to,
                'message_id' => $body['messages'][0]['id'] ?? null,
            ]);
        } catch (GuzzleException $e) {
            Log::error('WhatsApp sendImageWithCaption error: '.$e->getMessage(), [
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
