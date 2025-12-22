<?php

namespace App\Services;

use App\Enums\WhatsAppCommand;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WhatsAppWebhookService
{
    public function __construct(
        protected CatalogService $catalogService,
        protected string $verifyToken
    ) {}

    public function verifyWebhook(string $mode, string $token, string $challenge): ?string
    {
        Log::info('WhatsApp verification attempt', [
            'mode' => $mode,
            'token_match' => $token === $this->verifyToken,
        ]);

        if ($mode === 'subscribe' && $token === $this->verifyToken) {
            return $challenge;
        }

        Log::warning('Verification failed: tokens do not match');

        return null;
    }

    public function handlePayload(array $payload, MessagingService $messaging): void
    {
        Log::info('Incoming WhatsApp payload', ['payload' => $payload]);

        $entries = $payload['entry'] ?? [];
        foreach ($entries as $entry) {
            $changes = $entry['changes'] ?? [];
            foreach ($changes as $change) {
                $messages = $change['value']['messages'] ?? [];
                foreach ($messages as $message) {
                    $this->processMessage($message, $messaging);
                }
            }
        }
    }

    protected function processMessage(array $message, MessagingService $messaging): void
    {
        $from = $message['from'];
        $type = $message['type'];
        $messageId = $message['id'];

        Log::info("Processing message {$messageId}", ['from' => $from, 'type' => $type]);

        // Позначаємо як прочитане та вмикаємо індикатор друку
        if (method_exists($messaging, 'markReadAndSendTypingIndicator')) {
            $messaging->markReadAndSendTypingIndicator($messageId);
        }

        match ($type) {
            'interactive' => $this->handleInteractive($from, $message['interactive'], $messaging),
            'text' => $this->handleText($from, $message['text']['body'], $messaging),
            default => Log::info("Unhandled message type: {$type}")
        };
    }

    protected function handleText(string $from, string $body, MessagingService $messaging): void
    {
        $text = strtolower(trim($body));
        Log::info('Text command received', ['from' => $from, 'body' => $text]);

        $parts = explode(' ', $text, 2);
        $commandText = $parts[0];
        $argument = $parts[1] ?? null;

        $command = WhatsAppCommand::fromText($commandText);

        try {
            match ($command) {
                WhatsAppCommand::Catalog => $messaging->sendCatalog($from),
                WhatsAppCommand::Groups => $messaging->sendGroups($from),
                WhatsAppCommand::Items => $messaging->sendItems($from, $argument),
                WhatsAppCommand::Item => $this->sendItemDetails($from, $argument, $messaging),
                default => $messaging->sendWelcomeMenu($from),
            };
        } catch (\Exception $e) {
            $this->logAndReportError($from, 'Command execution failed', $e, $messaging);
        }
    }

    protected function handleInteractive(string $from, array $interactive, MessagingService $messaging): void
    {
        $type = $interactive['type'];
        $selectedId = $interactive[$type]['id'] ?? ($interactive[$type]['id'] ?? null);

        Log::info('Interactive response', ['from' => $from, 'type' => $type, 'id' => $selectedId]);

        if (! $selectedId) {
            return;
        }

        try {
            if ($selectedId === 'back_to_menu' || str_starts_with($selectedId, 'menu_')) {
                $command = WhatsAppCommand::fromMenuId($selectedId);
                $this->handleText($from, $command?->value ?? 'help', $messaging);
            } elseif (str_starts_with($selectedId, 'group_')) {
                $messaging->sendItems($from, Str::after($selectedId, 'group_'));
            } elseif (str_starts_with($selectedId, 'item_')) {
                $this->sendItemDetails($from, Str::after($selectedId, 'item_'), $messaging);
            } elseif (str_starts_with($selectedId, 'next_page_')) {
                $this->handlePagination($from, $selectedId, $messaging);
            } elseif (str_starts_with($selectedId, 'back_to_list_')) {
                $messaging->sendItems($from, Str::after($selectedId, 'back_to_list_'));
            }
        } catch (\Exception $e) {
            $this->logAndReportError($from, 'Interactive handler failed', $e, $messaging);
        }
    }

    protected function sendItemDetails(string $from, ?string $slug, MessagingService $messaging): void
    {
        if (! $slug) {
            $messaging->sendMessage($from, 'Please provide an item slug.');

            return;
        }

        $item = $this->catalogService->getItem($slug);
        if ($item) {
            $messaging->sendItemDetails($from, $item);
        } else {
            Log::warning('Item not found', ['slug' => $slug]);
            $messaging->sendMessage($from, "Item '{$slug}' not found.");
        }
    }

    protected function handlePagination(string $from, string $id, MessagingService $messaging): void
    {
        // Формат: next_page_{groupSlug}_{pageNumber}
        $parts = explode('_', $id);
        if (count($parts) >= 4) {
            $group = $parts[2] === 'all' ? null : $parts[2];
            $page = (int) $parts[3];
            Log::info('Pagination triggered', ['group' => $group, 'page' => $page]);
            $messaging->sendItems($from, $group, $page);
        }
    }

    protected function logAndReportError(string $from, string $ctx, \Exception $e, MessagingService $messaging): void
    {
        Log::error("{$ctx}: ".$e->getMessage(), ['from' => $from, 'trace' => $e->getTraceAsString()]);

        try {
            $messaging->sendMessage($from, 'An error occurred. Please try again later.');
        } catch (\Exception $silent) {
            Log::error('Failed to send error notification', ['error' => $silent->getMessage()]);
        }
    }
}
