<?php

namespace App\Http\Integrations\WhatsApp\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class MarkMessageAsReadRequest extends Request implements HasBody
{
    use HasJsonBody;

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
