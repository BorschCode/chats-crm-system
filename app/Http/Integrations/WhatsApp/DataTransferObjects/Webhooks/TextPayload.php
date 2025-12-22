<?php

namespace App\Http\Integrations\WhatsApp\DataTransferObjects\Webhooks;

class TextPayload
{
    public function __construct(
        public readonly string $body
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            body: $data['body']
        );
    }
}
