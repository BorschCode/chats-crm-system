<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Chats Catalog System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100">
<div
    class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 selection:bg-red-500 selection:text-white">
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="flex justify-center">
            <h1 class="text-4xl font-bold text-gray-900">Product Catalog</h1>
        </div>

        <div class="mt-8">
            <div class="grid grid-cols-1 gap-6 lg:gap-8">
                @if (Route::currentRouteName() === 'home' || Route::currentRouteName() === 'items.list' || Route::currentRouteName() === 'items.by.group')
                    @livewire(\App\Livewire\ItemList::class, ['groupSlug' => Route::current()->parameter('groupSlug')])
                @elseif (Route::currentRouteName() === 'groups.list')
                    @livewire(\App\Livewire\GroupList::class)
                @elseif (Route::currentRouteName() === 'item.show')
                    @livewire(\App\Livewire\ItemShow::class, ['slug' => Route::current()->parameter('slug')])
                @endif
            </div>
        </div>

        <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
            <div class="text-center text-sm text-gray-500 sm:text-left">
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" wire:navigate
                       class="group inline-flex items-center hover:text-gray-700 focus:outline focus:outline-2 focus:outline-red-500">
                        All Items
                    </a>
                    <a href="{{ route('groups.list') }}" wire:navigate
                       class="group inline-flex items-center hover:text-gray-700 focus:outline focus:outline-2 focus:outline-red-500">
                        All Groups
                    </a>
                </div>
            </div>

            <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div>
        </div>
    </div>
</div>
</body>
</html>
