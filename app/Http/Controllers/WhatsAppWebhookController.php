<?php

namespace App\Http\Controllers;

use App\Services\MessagingService;
use App\Services\WhatsAppWebhookService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WhatsAppWebhookController extends Controller
{
    public function __construct(
        protected MessagingService $messagingService,
        protected WhatsAppWebhookService $webhookService
    ) {}

    public function verify(Request $request): Response
    {
        $verified = $this->webhookService->verifyWebhook(
            $request->query('hub_mode', ''),
            $request->query('hub_verify_token', ''),
            $request->query('hub_challenge', '')
        );

        return $verified
            ? response($verified, Response::HTTP_OK)->header('Content-Type', 'text/plain')
            : response('', Response::HTTP_FORBIDDEN);
    }

    public function handle(Request $request): Response
    {
        try {
            $this->webhookService->handlePayload($request->all(), $this->messagingService);

            return response('', Response::HTTP_OK);
        } catch (\Exception $exception) {
            \Illuminate\Support\Facades\Log::error('WhatsApp webhook handler exception', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
