<?php

/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\BotHandlers;
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
