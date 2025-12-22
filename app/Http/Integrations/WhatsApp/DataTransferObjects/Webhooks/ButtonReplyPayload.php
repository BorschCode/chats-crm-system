<?php

namespace App\Http\Integrations\WhatsApp\DataTransferObjects\Webhooks;

class ButtonReplyPayload
{
    public function __construct(
        public readonly string $id,
        public readonly string $text
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            text: $data['text']
        );
    }
}
