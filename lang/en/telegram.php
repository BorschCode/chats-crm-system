<?php

return [
    'settings' => [
        'title' => 'Settings',
        'language' => 'Language',
        'gender' => 'Gender',
        'close' => 'Close',
        'back' => 'Back',
        'updated' => 'Settings updated!',
        'select_language' => 'Select your language:',
        'select_gender' => 'Select your gender:',
        'current_language' => 'Current language',
        'current_gender' => 'Current gender',
    ],

    'language' => [
        'select' => 'Select your language:',
        'changed' => 'Language changed to :language',
    ],

    'gender' => [
        'male' => 'Male',
        'female' => 'Female',
        'prefer_not_to_say' => 'Prefer not to say',
        'changed' => 'Gender preference updated',
        'not_set' => 'Not set',
    ],

    'commands' => [
        'start' => [
            'message' => "Welcome! 👋\n\nTap the button below to browse our catalog in an interactive app.",
            'button' => '🛍️ Open Catalog',
        ],
        'catalog' => [
            'message' => '🛍️ Opening catalog...',
            'button' => '🛍️ Open Catalog',
        ],
        'item_not_found' => 'Item not found. Use /start to browse the catalog.',
    ],

    'welcome' => 'Welcome!',
    'help' => "I didn't recognize that command.\n\nUse /start to browse the catalog.",
    'error' => 'Sorry, something went wrong. Please try again or use /start to open the catalog.',
];
