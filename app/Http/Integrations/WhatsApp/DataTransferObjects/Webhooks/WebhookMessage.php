<?php

namespace App\Http\Integrations\WhatsApp\DataTransferObjects\Webhooks;

class WebhookMessage
{
    public function __construct(
        public readonly string $id,
        public readonly string $from,
        public readonly string $timestamp,
        public readonly string $type,
        public readonly ?TextPayload $text = null,
        public readonly ?InteractivePayload $interactive = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            from: $data['from'],
            timestamp: $data['timestamp'],
            type: $data['type'],
            text: isset($data['text']) ? TextPayload::fromArray($data['text']) : null,
            interactive: isset($data['interactive']) ? InteractivePayload::fromArray($data['interactive']) : null
        );
    }
}
