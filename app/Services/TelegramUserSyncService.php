<?php

namespace App\Services;

use App\Enums\Language;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SergiX44\Nutgram\Nutgram;

class TelegramUserSyncService
{
    /**
     * Sync or create user from Telegram bot instance
     */
    public function syncFromBot(Nutgram $bot): User
    {
        $chatId = (string) $bot->chatId();
        $telegramUser = $bot->user();

        $userData = [
            'first_name' => $telegramUser?->first_name ?? 'Telegram User',
            'last_name' => $telegramUser?->last_name,
            'username' => $telegramUser?->username,
        ];

        return $this->findOrCreateUser($chatId, $userData);
    }

    /**
     * Find or create user by Telegram chat ID
     */
    public function findOrCreateUser(string $chatId, array $telegramData): User
    {
        $user = User::byTelegramChatId($chatId)->first();

        if ($user) {
            $this->updateUserData($user, $telegramData);

            return $user;
        }

        return $this->createUser($chatId, $telegramData);
    }

    /**
     * Create a new user from Telegram data
     */
    protected function createUser(string $chatId, array $telegramData): User
    {
        $name = $this->extractName($telegramData);
        $email = $this->generateEmail($telegramData['username'] ?? $chatId);

        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(Str::random(32)),
            'telegram_chat_id' => $chatId,
            'telegram_username' => $telegramData['username'] ?? null,
            'language' => Language::English,
        ]);
    }

    /**
     * Update existing user with latest Telegram data
     */
    protected function updateUserData(User $user, array $telegramData): void
    {
        $updates = [];

        if (isset($telegramData['username']) && $user->telegram_username !== $telegramData['username']) {
            $updates['telegram_username'] = $telegramData['username'];
        }

        if (! empty($updates)) {
            $user->update($updates);
        }
    }

    /**
     * Extract name from Telegram data
     */
    protected function extractName(array $telegramData): string
    {
        $firstName = $telegramData['first_name'] ?? 'Telegram';
        $lastName = $telegramData['last_name'] ?? '';

        return trim("{$firstName} {$lastName}");
    }

    /**
     * Generate unique email for user
     */
    protected function generateEmail(string $identifier): string
    {
        $baseEmail = Str::slug($identifier).'@telegram.local';
        $counter = 1;
        $email = $baseEmail;

        while (User::where('email', $email)->exists()) {
            $email = Str::slug($identifier).".{$counter}@telegram.local";
            $counter++;
        }

        return $email;
    }
}
