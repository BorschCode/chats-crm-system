<?php

/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\BotHandlers;
use App\Telegram\Conversations\SettingsConversation;
use App\Telegram\Middleware\SetUserLocaleMiddleware;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;

/*
|--------------------------------------------------------------------------
| GLOBAL UPDATE LOGGER (FIRST)
|--------------------------------------------------------------------------
*/
$bot->onUpdate(function (Nutgram $bot) {
    Log::info('Nutgram update received', [
        'update_id' => $bot->update()->update_id ?? null,
        'type' => array_key_first((array) $bot->update()),
        'chat_id' => $bot->chatId(),
        'text' => $bot->message()?->text,
        'callback_data' => $bot->callbackQuery()?->data,
        'from' => [
            'id' => $bot->userId(),
            'username' => $bot->user()?->username,
            'language' => $bot->user()?->language_code,
        ],
    ]);
});

/*
|--------------------------------------------------------------------------
| CALLBACK QUERY LOGGER
|--------------------------------------------------------------------------
*/
$bot->onCallbackQuery(function (Nutgram $bot) {
    Log::info('Callback query received', [
        'chat_id' => $bot->chatId(),
        'data' => $bot->callbackQuery()?->data,
        'conversation' => $bot->getConversation(),
    ]);
});

/*
|--------------------------------------------------------------------------
| MIDDLEWARE
|--------------------------------------------------------------------------
*/
$bot->middleware(SetUserLocaleMiddleware::class);

/*
|--------------------------------------------------------------------------
| COMMANDS
|--------------------------------------------------------------------------
*/
$handlers = app(BotHandlers::class);
$handlers->registerHandlers($bot);

$bot->onCommand('settings', function (Nutgram $bot) {
    Log::info('Settings command triggered', [
        'chat_id' => $bot->chatId(),
    ]);

    SettingsConversation::begin($bot);
});

/*
|--------------------------------------------------------------------------
| FALLBACK (LAST, ALWAYS LAST)
|--------------------------------------------------------------------------
*/
$bot->fallback(function (Nutgram $bot) {
    Log::warning('Fallback triggered', [
        'chat_id' => $bot->chatId(),
        'text' => $bot->message()?->text,
        'callback_data' => $bot->callbackQuery()?->data,
        'conversation' => $bot->getConversation(),
        'update' => $bot->update(),
    ]);

    $bot->sendMessage(
        text: __('telegram.help'),
        chat_id: $bot->chatId()
    );
});
