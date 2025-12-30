<?php

namespace App\Services;

use App\Enums\Gender;
use App\Enums\Language;
use App\Models\User;
use Illuminate\Support\Collection;

class SettingsService
{
    /**
     * Get user settings as an array
     */
    public function getUserSettings(User $user): array
    {
        return [
            'language' => $user->language?->value ?? config('app.locale'),
            'gender' => $user->gender?->value,
            'language_display' => $user->language?->getDisplayText() ?? Language::English->getDisplayText(),
            'gender_display' => $user->gender?->getDisplayText() ?? trans('telegram.gender.not_set'),
        ];
    }

    /**
     * Update user's language preference
     */
    public function updateLanguage(User $user, Language $language): void
    {
        $user->language = $language;
        $user->save();
    }

    /**
     * Update user's gender preference
     */
    public function updateGender(User $user, ?Gender $gender): void
    {
        $user->gender = $gender;
        $user->save();
    }

    /**
     * Get all available languages
     */
    public function getAvailableLanguages(): Collection
    {
        return collect(Language::cases());
    }

    /**
     * Get all available genders
     */
    public function getAvailableGenders(): Collection
    {
        return collect(Gender::cases());
    }

    /**
     * Format current settings for display
     */
    public function formatCurrentSettings(User $user, string $locale): string
    {
        $language = $user->language?->getDisplayText() ?? trans('telegram.gender.not_set');
        $gender = $user->gender?->getDisplayText() ?? trans('telegram.gender.not_set');

        return trans('telegram.settings.current_language').": {$language}\n".
               trans('telegram.settings.current_gender').": {$gender}";
    }
}
