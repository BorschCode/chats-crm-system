<?php

namespace App\Http\Integrations\WhatsApp\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SendInteractiveButtonsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $phoneNumberId,
        protected string $to,
        protected array $buttonData
    ) {}

    public function resolveEndpoint(): string
    {
        return "{$this->phoneNumberId}/messages";
    }

    protected function defaultBody(): array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'body' => [
                    'text' => $this->buttonData['body'],
                ],
                'action' => [
                    'buttons' => $this->buttonData['buttons'],
                ],
            ],
        ];

        if (isset($this->buttonData['header'])) {
            $payload['interactive']['header'] = [
                'type' => 'text',
                'text' => $this->buttonData['header'],
            ];
        }

        if (isset($this->buttonData['footer'])) {
            $payload['interactive']['footer'] = [
                'text' => $this->buttonData['footer'],
            ];
        }

        return $payload;
    }
}
