<?php

namespace App\Services;

use App\Enums\WhatsAppCommand;
use App\Http\Integrations\WhatsApp\DataTransferObjects\Responses\MessageResponse;
use App\Http\Integrations\WhatsApp\Exceptions\AccessTokenInvalidException;
use App\Http\Integrations\WhatsApp\Exceptions\MessageTooLongException;
use App\Http\Integrations\WhatsApp\Exceptions\RecipientNotAllowedException;
use App\Http\Integrations\WhatsApp\Requests\MarkMessageAsReadRequest;
use App\Http\Integrations\WhatsApp\Requests\SendImageMessageRequest;
use App\Http\Integrations\WhatsApp\Requests\SendInteractiveButtonsRequest;
use App\Http\Integrations\WhatsApp\Requests\SendInteractiveListRequest;
use App\Http\Integrations\WhatsApp\Requests\SendTextMessageRequest;
use App\Http\Integrations\WhatsApp\WhatsAppConnector;
use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Saloon\Exceptions\Request\RequestException;

class WhatsAppService implements MessagingService
{
    protected CatalogService $catalogService;

    protected WhatsAppConnector $connector;

    public function __construct(
        CatalogService $catalogService,
        WhatsAppConnector $connector
    ) {
        $this->catalogService = $catalogService;
        $this->connector = $connector;
    }

    public function markReadAndSendTypingIndicator(string $messageId, string $recipient): void
    {
        try {
            $request = new MarkMessageAsReadRequest(
                phoneNumberId: $this->connector->phoneNumberId,
                messageId: $messageId
            );

            $response = $this->connector->send($request);

            Log::info('WhatsApp: Message marked as read (typing indicator)', [
                'message_id' => $messageId,
                'recipient' => $recipient,
                'response' => $response->json(),
            ]);
        } catch (\Exception $exception) {
            Log::warning('WhatsApp: Failed to mark message as read - '.$exception->getMessage(), [
                'message_id' => $messageId,
                'recipient' => $recipient,
            ]);
            // Don't throw - typing indicators are not critical
        }
    }

    public function sendInteractiveList(string $to, array $listData): void
    {
        try {
            $request = new SendInteractiveListRequest(
                phoneNumberId: $this->connector->phoneNumberId,
                to: $to,
                listData: $listData
            );

            $response = $this->connector->send($request);
            $messageResponse = MessageResponse::fromResponse($response);

            Log::info('WhatsApp interactive list sent successfully', [
                'to' => $to,
                'message_id' => $messageResponse->getMessageId(),
            ]);
        } catch (RecipientNotAllowedException $e) {
            Log::warning($e->getMessage(), ['to' => $to]);
            throw $e;
        } catch (AccessTokenInvalidException $e) {
            Log::error($e->getMessage());
            throw $e;
        } catch (MessageTooLongException $e) {
            Log::error($e->getMessage(), ['to' => $to]);
            throw $e;
        } catch (RequestException $e) {
            Log::error('WhatsApp sendInteractiveList error: '.$e->getMessage(), [
                'to' => $to,
                'list_data' => $listData,
            ]);
            throw $e;
        }
    }

    public function sendInteractiveButtons(string $to, array $buttonData): void
    {
        try {
            $request = new SendInteractiveButtonsRequest(
                phoneNumberId: $this->connector->phoneNumberId,
                to: $to,
                buttonData: $buttonData
            );

            $response = $this->connector->send($request);
            $messageResponse = MessageResponse::fromResponse($response);

            Log::info('WhatsApp interactive buttons sent successfully', [
                'to' => $to,
                'message_id' => $messageResponse->getMessageId(),
            ]);
        } catch (RecipientNotAllowedException $e) {
            Log::warning($e->getMessage(), ['to' => $to]);
            throw $e;
        } catch (AccessTokenInvalidException $e) {
            Log::error($e->getMessage());
            throw $e;
        } catch (MessageTooLongException $e) {
            Log::error($e->getMessage(), ['to' => $to]);
            throw $e;
        } catch (RequestException $e) {
            Log::error('WhatsApp sendInteractiveButtons error: '.$e->getMessage(), [
                'to' => $to,
                'button_data' => $buttonData,
            ]);
            throw $e;
        }
    }

    public function sendMessage(string $to, string $text): void
    {
        try {
            $request = new SendTextMessageRequest(
                phoneNumberId: $this->connector->phoneNumberId,
                to: $to,
                text: $text
            );

            $response = $this->connector->send($request);
            $messageResponse = MessageResponse::fromResponse($response);

            Log::info('WhatsApp message sent successfully', [
                'to' => $to,
                'message_id' => $messageResponse->getMessageId(),
            ]);
        } catch (RecipientNotAllowedException $e) {
            Log::warning('WhatsApp: Recipient not in allowed list (test mode restriction)', [
                'to' => $to,
                'error_code' => $e->getCode(),
                'hint' => 'Add this number to your WhatsApp Business API allowed list in Meta Developer Console',
                'docs' => 'https://developers.facebook.com/docs/whatsapp/cloud-api/get-started#send-messages',
            ]);
            throw $e;
        } catch (AccessTokenInvalidException $e) {
            Log::error('WhatsApp: Access token expired or invalid', [
                'error_code' => $e->getCode(),
                'hint' => 'Generate a new access token in Meta Developer Console',
            ]);
            throw $e;
        } catch (MessageTooLongException $e) {
            Log::error('WhatsApp: Message too long (exceeds 4096 character limit)', [
                'error_code' => $e->getCode(),
                'message_length' => strlen($text),
                'hint' => 'Message should be split into multiple messages with pagination',
            ]);
            throw $e;
        } catch (RequestException $e) {
            Log::error('WhatsApp sendMessage error: '.$e->getMessage(), [
                'to' => $to,
                'text' => $text,
            ]);
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
                            'id' => WhatsAppCommand::MenuCatalog->value,
                            'title' => 'Catalog',
                            'description' => 'Browse product groups',
                        ],
                        [
                            'id' => WhatsAppCommand::MenuGroups->value,
                            'title' => 'Groups',
                            'description' => 'View all product groups',
                        ],
                        [
                            'id' => WhatsAppCommand::MenuItems->value,
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
            $rows[] = [
                'id' => "item_{$item->slug}",
                'title' => $this->truncateWords($item->title, 24),
                'description' => '$'.number_format($item->price, 2),
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

    protected function truncateWords(string $text, int $maxLength): string
    {
        if (mb_strlen($text) <= $maxLength) {
            return $text;
        }

        $words = preg_split('/\s+/', $text);
        $result = '';

        foreach ($words as $word) {
            $candidate = trim($result.' '.$word);

            if (mb_strlen($candidate) > $maxLength) {
                break;
            }

            $result = $candidate;
        }

        return $result ?: mb_substr($text, 0, $maxLength);
    }

    public function sendItemDetails(string $to, Item $item): void
    {
        $groupTitle = $item->group ? $item->group->title : 'Uncategorized';
        $groupSlug = $item->group ? $item->group->slug : null;

        // Convert image path to absolute URL if needed
        $imageUrl = $this->getAbsoluteImageUrl($item->image);

        // Check if we have a valid image URL
        if ($imageUrl) {
            $this->sendImageWithCaption($to, $item, $groupTitle, $imageUrl);
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

        if ($groupSlug) {
            $title = $this->buildBackButtonTitle($groupTitle);

            $buttons[] = [
                'type' => 'reply',
                'reply' => [
                    'id' => "back_to_list_{$groupSlug}",
                    'title' => $title,
                ],
            ];
        }

        $buttons[] = [
            'type' => 'reply',
            'reply' => [
                'id' => WhatsAppCommand::BackToMenu->value,
                'title' => '🏠 Main Menu',
            ],
        ];

        try {
            $this->sendInteractiveButtons($to, [
                'body' => 'What would you like to do next?',
                'buttons' => $buttons,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Could not send navigation buttons', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function buildBackButtonTitle(string $groupTitle): string
    {
        $full = "⬅️ {$groupTitle}";

        // WhatsApp limit is 20 chars
        if (mb_strlen($full) <= 20) {
            return $full;
        }

        // Fallback — clean and readable
        return '⬅️ Back';
    }

    /**
     * Convert relative image path to absolute URL
     */
    protected function getAbsoluteImageUrl(?string $imagePath): ?string
    {
        if (! $imagePath) {
            return null;
        }

        // If already an absolute URL, return as-is
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        // If it's a relative path starting with /, convert to absolute URL
        if (str_starts_with($imagePath, '/')) {
            $baseUrl = rtrim(config('app.url'), '/');

            return $baseUrl.$imagePath;
        }

        // If it's a relative path without leading slash, add it
        $baseUrl = rtrim(config('app.url'), '/');

        return $baseUrl.'/'.$imagePath;
    }

    protected function sendImageWithCaption(string $to, Item $item, string $groupTitle, string $imageUrl): void
    {
        $caption = "*{$item->title}*\n\n";
        $caption .= "*Group:* {$groupTitle}\n";
        $caption .= "*Price:* \${$item->price}\n\n";
        $caption .= "*Description:*\n{$item->description}";

        try {
            $request = new SendImageMessageRequest(
                phoneNumberId: $this->connector->phoneNumberId,
                to: $to,
                imageUrl: $imageUrl,
                caption: $caption
            );

            $response = $this->connector->send($request);
            $messageResponse = MessageResponse::fromResponse($response);

            Log::info('WhatsApp: Image sent successfully', [
                'to' => $to,
                'message_id' => $messageResponse->getMessageId(),
                'image_url' => $imageUrl,
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp: Failed to send image - '.$e->getMessage(), [
                'to' => $to,
                'item_id' => $item->id,
                'image_url' => $imageUrl,
                'error' => $e->getMessage(),
            ]);

            // Fallback to text message
            $text = "*{$item->title}*\n\n";
            $text .= "*Group:* {$groupTitle}\n";
            $text .= "*Price:* \${$item->price}\n\n";
            $text .= "*Description:*\n{$item->description}";

            $this->sendMessage($to, $text);
        }
    }
}
