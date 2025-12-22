<?php

namespace App\Http\Integrations\WhatsApp\DataTransferObjects\Webhooks;

class InteractivePayload
{
    public function __construct(
        public readonly string $type,
        public readonly ?ListReplyPayload $listReply = null,
        public readonly ?ButtonReplyPayload $buttonReply = null
    ) {}

    public static function fromArray(array $data): self
    {
        $type = $data['type'];

        return new self(
            type: $type,
            listReply: $type === 'list_reply' && isset($data['list_reply'])
                ? ListReplyPayload::fromArray($data['list_reply'])
                : null,
            buttonReply: $type === 'button_reply' && isset($data['button_reply'])
                ? ButtonReplyPayload::fromArray($data['button_reply'])
                : null
        );
    }

    /**
     * Get the selected ID regardless of interactive type.
     * This replaces the fragile: $interactive[$type]['id'] ?? ($interactive[$type]['id'] ?? null)
     */
    public function getSelectedId(): ?string
    {
        return $this->listReply?->id ?? $this->buttonReply?->id;
    }

    /**
     * Get the selected title regardless of interactive type.
     */
    public function getSelectedTitle(): ?string
    {
        return $this->listReply?->title ?? $this->buttonReply?->text;
    }
}
