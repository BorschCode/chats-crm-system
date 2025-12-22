<?php

namespace App\Http\Integrations\WhatsApp\DataTransferObjects\Responses;

use Saloon\Http\Response;

class MessageResponse
{
    public function __construct(
        public readonly string $messagingProduct,
        public readonly array $contacts,
        public readonly array $messages
    ) {}

    /**
     * @throws \JsonException
     */
    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return new self(
            messagingProduct: $data['messaging_product'] ?? 'whatsapp',
            contacts: $data['contacts'] ?? [],
            messages: $data['messages'] ?? []
        );
    }

    /**
     * Get the message ID from the response.
     * Replaces: $body['messages'][0]['id'] ?? null
     */
    public function getMessageId(): ?string
    {
        return $this->messages[0]['id'] ?? null;
    }
}
