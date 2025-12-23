<?php

namespace App\Http\Integrations\WhatsApp;

use App\Http\Integrations\WhatsApp\Exceptions\AccessTokenInvalidException;
use App\Http\Integrations\WhatsApp\Exceptions\MessageTooLongException;
use App\Http\Integrations\WhatsApp\Exceptions\RecipientNotAllowedException;
use Saloon\Http\Connector;
use Saloon\Http\PendingRequest;
use Saloon\Http\Response;

class WhatsAppConnector extends Connector
{
    public function __construct(
        public readonly string $phoneNumberId,
        public readonly string $accessToken,
        public readonly string $apiVersion
    ) {}

    public function resolveBaseUrl(): string
    {
        return "https://graph.facebook.com/{$this->apiVersion}/";
    }

    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->accessToken}",
            'Content-Type' => 'application/json',
        ];
    }

    public function boot(PendingRequest $pendingRequest): void
    {
        $pendingRequest->middleware()->onResponse(function (Response $response) {
            if ($response->failed()) {
                $this->handleErrorResponse($response);
            }

            return $response;
        });
    }

    /**
     * @throws AccessTokenInvalidException
     * @throws MessageTooLongException
     * @throws \JsonException
     * @throws RecipientNotAllowedException
     * @throws \Exception
     */
    protected function handleErrorResponse(Response $response): void
    {
        $data = $response->json();
        $errorCode = $data['error']['code'] ?? null;
        $errorMessage = $data['error']['message'] ?? 'Unknown error';

        match ($errorCode) {
            131030 => throw new RecipientNotAllowedException($errorMessage),
            190 => throw new AccessTokenInvalidException,
            100 => str_contains($errorMessage, '4096')
                ? throw new MessageTooLongException(4096)
                : throw new \Exception($errorMessage, $errorCode),
            default => throw new \Exception($errorMessage, $errorCode ?? 0),
        };
    }
}
