<?php

namespace App\Enums;

enum SettingsKey: string
{
    case Language = 'language';
    case Gender = 'gender';

    /**
     * Get the icon emoji for this setting
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::Language => '🌐',
            self::Gender => '👤',
        };
    }

    /**
     * Get the translation key for this setting
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Language => 'telegram.settings.language',
            self::Gender => 'telegram.settings.gender',
        };
    }
}
