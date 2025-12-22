<?php

namespace App\Http\Integrations\WhatsApp\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class MarkMessageAsReadRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $phoneNumberId,
        protected string $messageId
    ) {}

    public function resolveEndpoint(): string
    {
        return "{$this->phoneNumberId}/messages";
    }

    protected function defaultBody(): array
    {
        return [
            'messaging_product' => 'whatsapp',
            'status' => 'read',
            'message_id' => $this->messageId,
            'typing_indicator' => [
                'type' => 'text',
            ],
        ];
    }
}
