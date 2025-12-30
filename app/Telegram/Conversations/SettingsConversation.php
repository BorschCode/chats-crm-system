<?php

namespace App\Telegram\Conversations;

use App\Enums\Gender;
use App\Enums\Language;
use App\Enums\SettingsKey;
use App\Models\User;
use App\Services\SettingsService;
use App\Services\TelegramUserSyncService;
use Illuminate\Support\Facades\App;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class SettingsConversation extends InlineMenu
{
    protected SettingsService $settingsService;

    protected TelegramUserSyncService $userSyncService;

    public function __construct()
    {
        parent::__construct();
        $this->settingsService = app(SettingsService::class);
        $this->userSyncService = app(TelegramUserSyncService::class);
    }

    /**
     * Main settings menu
     */
    public function start(Nutgram $bot): void
    {
        $user = $this->getUserFromBot($bot);
        App::setLocale($user->language->value);

        $text = trans('telegram.settings.title')."\n\n".
                SettingsKey::Language->getIcon().' '.trans('telegram.settings.language').': '.$user->language->getDisplayText()."\n".
                SettingsKey::Gender->getIcon().' '.trans('telegram.settings.gender').': '.($user->gender?->getDisplayText() ?? trans('telegram.gender.not_set'));

        $this->clearButtons()
            ->menuText($text)
            ->addButtonRow(InlineKeyboardButton::make(
                SettingsKey::Language->getIcon().' '.trans('telegram.settings.language'),
                callback_data: 'settings:language@showLanguageMenu'
            ))
            ->addButtonRow(InlineKeyboardButton::make(
                SettingsKey::Gender->getIcon().' '.trans('telegram.settings.gender'),
                callback_data: 'settings:gender@showGenderMenu'
            ))
            ->addButtonRow(InlineKeyboardButton::make(
                '❌ '.trans('telegram.settings.close'),
                callback_data: 'settings:close@close'
            ))
            ->showMenu();
    }

    /**
     * Language selection menu
     */
    public function showLanguageMenu(Nutgram $bot): void
    {
        $user = $this->getUserFromBot($bot);
        App::setLocale($user->language->value);

        $this->clearButtons()
            ->menuText(trans('telegram.language.select'));

        foreach (Language::cases() as $language) {
            $label = $language->getDisplayText();
            if ($user->language === $language) {
                $label .= ' ✅';
            }

            $this->addButtonRow(InlineKeyboardButton::make(
                $label,
                callback_data: "settings:lang:{$language->value}@handleLanguageSelection"
            ));
        }

        $this->addButtonRow(InlineKeyboardButton::make(
            '⬅️ '.trans('telegram.settings.back'),
            callback_data: 'settings:back@start'
        ))
            ->showMenu();
    }

    /**
     * Handle language selection
     */
    public function handleLanguageSelection(Nutgram $bot): void
    {
        $user = $this->getUserFromBot($bot);

        // Extract language code from callback data: settings:lang:uk@handleLanguageSelection
        $callbackData = $bot->callbackQuery()->data;
        // Remove @methodName part if present
        $callbackData = explode('@', $callbackData)[0];
        [, , $languageCode] = explode(':', $callbackData);
        \Log::info('Language selection', ['code' => $languageCode]);

        $language = Language::from($languageCode);
        \Log::info('Language enum', ['language' => $language->value, 'current' => $user->language?->value]);

        if ($user->language !== $language) {
            $this->settingsService->updateLanguage($user, $language);
            \Log::info('Language updated', ['user_id' => $user->id, 'new_language' => $language->value]);
            App::setLocale($language->value);

            $bot->answerCallbackQuery(
                text: trans('telegram.language.changed', ['language' => $language->getName()]),
                show_alert: false
            );
        } else {
            \Log::info('Language not changed - same as current');
        }

        $this->start($bot);
    }

    /**
     * Gender selection menu
     */
    public function showGenderMenu(Nutgram $bot): void
    {
        $user = $this->getUserFromBot($bot);
        App::setLocale($user->language->value);

        $this->clearButtons()
            ->menuText(trans('telegram.settings.select_gender'));

        foreach (Gender::cases() as $gender) {
            $label = $gender->getDisplayText();
            if ($user->gender === $gender) {
                $label .= ' ✅';
            }

            $this->addButtonRow(InlineKeyboardButton::make(
                $label,
                callback_data: "settings:gnd:{$gender->value}@handleGenderSelection"
            ));
        }

        $this->addButtonRow(InlineKeyboardButton::make(
            '⬅️ '.trans('telegram.settings.back'),
            callback_data: 'settings:back@start'
        ))
            ->showMenu();
    }

    /**
     * Handle gender selection
     */
    public function handleGenderSelection(Nutgram $bot): void
    {
        $user = $this->getUserFromBot($bot);
        App::setLocale($user->language->value);

        // Extract gender value from callback data: settings:gnd:male@handleGenderSelection
        $callbackData = $bot->callbackQuery()->data;
        // Remove @methodName part if present
        $callbackData = explode('@', $callbackData)[0];
        [, , $genderValue] = explode(':', $callbackData);

        $gender = Gender::from($genderValue);

        if ($user->gender !== $gender) {
            $this->settingsService->updateGender($user, $gender);

            $bot->answerCallbackQuery(
                text: trans('telegram.gender.changed'),
                show_alert: false
            );
        }

        $this->start($bot);
    }

    /**
     * Close settings menu
     */
    public function close(Nutgram $bot): void
    {
        $user = $this->getUserFromBot($bot);
        App::setLocale($user->language->value);

        $this->closeMenu();
    }

    /**
     * Get or create user from bot instance
     */
    protected function getUserFromBot(Nutgram $bot): User
    {
        return $this->userSyncService->syncFromBot($bot);
    }
}
