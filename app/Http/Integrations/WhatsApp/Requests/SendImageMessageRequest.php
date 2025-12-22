<?php

namespace App\Http\Integrations\WhatsApp\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class SendImageMessageRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $phoneNumberId,
        protected string $to,
        protected string $imageUrl,
        protected string $caption
    ) {}

    public function resolveEndpoint(): string
    {
        return "{$this->phoneNumberId}/messages";
    }

    protected function defaultBody(): array
    {
        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'image',
            'image' => [
                'link' => $this->imageUrl,
                'caption' => $this->caption,
            ],
        ];
    }
}
