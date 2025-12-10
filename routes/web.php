<?php

use App\Livewire\GroupList;
use App\Livewire\ItemList;
use App\Livewire\ItemShow;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Catalog Routes
Route::get('/', ItemList::class)->name('home');
Route::get('/groups', GroupList::class)->name('groups.list');
Route::get('/items', ItemList::class)->name('items.list');
Route::get('/items/{groupSlug}', ItemList::class)->name('items.by.group');
Route::get('/item/{slug}', ItemShow::class)->name('item.show');
