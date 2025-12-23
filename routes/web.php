<?php

use App\Livewire\About;
use App\Livewire\GroupList;
use App\Livewire\ItemList;
use App\Livewire\ItemShow;
use Illuminate\Support\Facades\Route;

// Catalog Routes
Route::get('/', ItemList::class)->name('home');
Route::get('/groups', GroupList::class)->name('groups.list');
Route::get('/items', ItemList::class)->name('items.list');
Route::get('/items/{groupSlug}', ItemList::class)->name('items.by.group');
Route::get('/item/{slug}', ItemShow::class)->name('item.show');
Route::get('/about', About::class)->name('about');

// Telegram Mini App
Route::get('/telegram/app', function () {
    return view('telegram.app');
})->name('telegram.app');
