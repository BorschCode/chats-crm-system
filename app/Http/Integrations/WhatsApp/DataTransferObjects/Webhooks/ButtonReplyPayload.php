<?php

namespace App\Http\Integrations\WhatsApp\DataTransferObjects\Webhooks;

class ButtonReplyPayload
{
    public function __construct(
        public readonly string $id,
        public readonly string $title
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            title: $data['title']
        );
    }
}
