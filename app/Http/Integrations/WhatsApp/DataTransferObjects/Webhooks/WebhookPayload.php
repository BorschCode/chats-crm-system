<?php

namespace App\Http\Integrations\WhatsApp\DataTransferObjects\Webhooks;

class WebhookPayload
{
    /**
     * @param  WebhookEntry[]  $entries
     */
    public function __construct(
        public readonly string $object,
        public readonly array $entries
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            object: $data['object'] ?? 'whatsapp_business_account',
            entries: array_map(
                fn (array $entry) => WebhookEntry::fromArray($entry),
                $data['entry'] ?? []
            )
        );
    }

    /**
     * Get all messages from all entries.
     *
     * @return WebhookMessage[]
     */
    public function getMessages(): array
    {
        $messages = [];

        foreach ($this->entries as $entry) {
            foreach ($entry->changes as $change) {
                $messages = array_merge($messages, $change->getMessages());
            }
        }

        return $messages;
    }
}
