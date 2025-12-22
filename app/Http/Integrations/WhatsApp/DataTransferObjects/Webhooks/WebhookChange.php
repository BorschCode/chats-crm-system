<?php

namespace App\Http\Integrations\WhatsApp\DataTransferObjects\Webhooks;

class WebhookChange
{
    public function __construct(
        public readonly array $value
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            value: $data['value'] ?? []
        );
    }

    /**
     * Get messages from this change.
     *
     * @return WebhookMessage[]
     */
    public function getMessages(): array
    {
        $messages = $this->value['messages'] ?? [];

        return array_map(
            fn (array $message) => WebhookMessage::fromArray($message),
            $messages
        );
    }
}
