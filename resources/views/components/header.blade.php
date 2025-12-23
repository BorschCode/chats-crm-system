<header class="bg-white shadow-sm border-b border-orange-100">
    <div class="container mx-auto px-4">
        <nav class="flex items-center justify-between h-16">
            {{-- Logo/Brand --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" wire:navigate class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 transition-all">
                    Product Catalog
                </a>
            </div>

            {{-- Navigation Links --}}
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}"
                   wire:navigate
                   class="text-gray-700 hover:text-orange-600 font-medium transition-colors {{ request()->routeIs('home') || request()->routeIs('items.*') ? 'text-orange-600 border-b-2 border-orange-600 pb-1' : '' }}">
                    Home
                </a>
                <a href="{{ route('groups.list') }}"
                   wire:navigate
                   class="text-gray-700 hover:text-orange-600 font-medium transition-colors {{ request()->routeIs('groups.*') ? 'text-orange-600 border-b-2 border-orange-600 pb-1' : '' }}">
                    Categories
                </a>
                <a href="{{ route('about') }}"
                   wire:navigate
                   class="text-gray-700 hover:text-orange-600 font-medium transition-colors {{ request()->routeIs('about') ? 'text-orange-600 border-b-2 border-orange-600 pb-1' : '' }}">
                    About
                </a>
            </div>
        </nav>
    </div>
</header>
