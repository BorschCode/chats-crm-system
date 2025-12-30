<?php

/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\BotHandlers;
use App\Telegram\Conversations\SettingsConversation;
use App\Telegram\Middleware\SetUserLocaleMiddleware;
use SergiX44\Nutgram\Nutgram;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/
// Register middleware to set user locale
$bot->middleware(SetUserLocaleMiddleware::class);

// Register all bot handlers
$handlers = app(BotHandlers::class);
$handlers->registerHandlers($bot);
// Settings command
$bot->onCommand('settings', function (Nutgram $bot) {
    SettingsConversation::begin($bot);
});

$bot->fallback(function (Nutgram $bot) {
    $bot->sendMessage(
        text: "Base error handler. I didn't recognize that command.\n\nUse /start to browse the catalog.",
        chat_id: $bot->chatId()
    );
});
