<?php

namespace App\Http\Integrations\WhatsApp\Exceptions;

class RecipientNotAllowedException extends \Exception
{
    public function __construct(string $errorMessage)
    {
        parent::__construct(
            "Recipient not in allowed list (WhatsApp test mode restriction): {$errorMessage}. Add this number to your WhatsApp Business API allowed list in Meta Developer Console.",
            131030
        );
    }
}
