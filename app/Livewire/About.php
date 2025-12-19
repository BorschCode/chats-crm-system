<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\View as Viewilluminate;
use Livewire\Component;

class About extends Component
{
    public function render(): Factory|View|Viewilluminate
    {
        $whatsappPhone = config('services.whatsapp.business_phone');
        $telegramUsername = config('services.telegram.bot_username');
        $instagramUsername = config('services.instagram.username');
        $defaultMessage = urlencode('start');

        return view('livewire.about', [
            // WhatsApp
            'whatsappPhone' => $whatsappPhone,
            'whatsappClickToChat' => "https://wa.me/{$whatsappPhone}?text={$defaultMessage}",
            'whatsappQrCodeUrl' => "https://wa.me/{$whatsappPhone}?text={$defaultMessage}",

            // Telegram
            'telegramUsername' => $telegramUsername,
            'telegramClickToChat' => "https://t.me/{$telegramUsername}",

            // Instagram
            'instagramUsername' => $instagramUsername,
            'instagramClickToChat' => "https://ig.me/m/{$instagramUsername}",
        ]);
    }
}
