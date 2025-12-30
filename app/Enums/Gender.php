<?php

namespace App\Enums;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';
    case PreferNotToSay = 'prefer_not_to_say';

    /**
     * Get the emoji for this gender
     */
    public function getEmoji(): string
    {
        return match ($this) {
            self::Male => '♂️',
            self::Female => '♀️',
            self::PreferNotToSay => '❓',
        };
    }

    /**
     * Get the translation key for this gender
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Male => 'telegram.gender.male',
            self::Female => 'telegram.gender.female',
            self::PreferNotToSay => 'telegram.gender.prefer_not_to_say',
        };
    }

    /**
     * Get the display text with emoji
     */
    public function getDisplayText(): string
    {
        return $this->getEmoji().' '.trans($this->getLabel());
    }
}
