<?php

namespace App\Enums;

enum Language: string
{
    case English = 'en';
    case Ukrainian = 'uk';
    case Russian = 'ru';
    case German = 'de';
    case Romanian = 'ro';
    case Georgian = 'ka';

    /**
     * Get the flag emoji for this language
     */
    public function getFlag(): string
    {
        return match ($this) {
            self::English => '🇬🇧',
            self::Ukrainian => '🇺🇦',
            self::Russian => '🇷🇺',
            self::German => '🇩🇪',
            self::Romanian => '🇷🇴',
            self::Georgian => '🇬🇪',
        };
    }

    /**
     * Get the native name of this language
     */
    public function getName(): string
    {
        return match ($this) {
            self::English => 'English',
            self::Ukrainian => 'Українська',
            self::Russian => 'Русский',
            self::German => 'Deutsch',
            self::Romanian => 'Română',
            self::Georgian => 'ქართული',
        };
    }

    /**
     * Get the display text with flag and name
     */
    public function getDisplayText(): string
    {
        return $this->getFlag().' '.$this->getName();
    }
}
