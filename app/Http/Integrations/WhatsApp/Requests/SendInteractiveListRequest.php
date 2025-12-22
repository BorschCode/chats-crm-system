<?php

namespace App\Http\Integrations\WhatsApp\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SendInteractiveListRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $phoneNumberId,
        protected string $to,
        protected array $listData
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
                'type' => 'list',
                'body' => [
                    'text' => $this->listData['body'],
                ],
                'action' => [
                    'button' => $this->listData['button'],
                    'sections' => $this->listData['sections'],
                ],
            ],
        ];

        if (isset($this->listData['header'])) {
            $payload['interactive']['header'] = [
                'type' => 'text',
                'text' => $this->listData['header'],
            ];
        }

        if (isset($this->listData['footer'])) {
            $payload['interactive']['footer'] = [
                'text' => $this->listData['footer'],
            ];
        }

        return $payload;
    }
}
