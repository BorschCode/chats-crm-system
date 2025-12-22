<?php

namespace App\Http\Integrations\WhatsApp\Exceptions;

class MessageTooLongException extends \Exception
{
    public function __construct(int $limit)
    {
        parent::__construct(
            "Message too long (exceeds {$limit} character limit). Message should be split into multiple messages.",
            100
        );
    }
}
