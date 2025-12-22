<?php

namespace App\Http\Integrations\WhatsApp\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class SendTextMessageRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $phoneNumberId,
        protected string $to,
        protected string $text,
        protected bool $previewUrl = false
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
            'type' => 'text',
            'text' => [
                'preview_url' => $this->previewUrl,
                'body' => $this->text,
            ],
        ];
    }
}
