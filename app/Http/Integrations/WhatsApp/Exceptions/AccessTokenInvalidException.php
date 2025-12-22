<?php

namespace App\Http\Integrations\WhatsApp\Exceptions;

class AccessTokenInvalidException extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            'Access token expired or invalid. Generate a new access token in Meta Developer Console.',
            190
        );
    }
}
