<?php

namespace App\Http\Integrations\WhatsApp\DataTransferObjects\Webhooks;

class ListReplyPayload
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly ?string $description = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            title: $data['title'],
            description: $data['description'] ?? null
        );
    }
}
