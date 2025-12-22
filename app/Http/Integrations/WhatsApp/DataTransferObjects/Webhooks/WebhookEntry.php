<?php

namespace App\Http\Integrations\WhatsApp\DataTransferObjects\Webhooks;

class WebhookEntry
{
    /**
     * @param  WebhookChange[]  $changes
     */
    public function __construct(
        public readonly string $id,
        public readonly array $changes
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? '',
            changes: array_map(
                fn (array $change) => WebhookChange::fromArray($change),
                $data['changes'] ?? []
            )
        );
    }
}
