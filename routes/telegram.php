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

// Register all bot handlers
$handlers = app(BotHandlers::class);
$handlers->registerHandlers($bot);
// Register middleware to set user locale
$bot->middleware(SetUserLocaleMiddleware::class);
// Settings command
$bot->onCommand('settings', function (Nutgram $bot) {
    SettingsConversation::begin($bot);
});
