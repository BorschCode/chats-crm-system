@php
    /**
     * View: livewire.item-list
     *
     * Variables provided by App\Livewire\ItemList::render()
     *
     * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator<\App\Models\Item> $items
     * @var \App\Models\Item $item
     * @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
     * @var string|null $groupSlug
     *
     * Livewire reactive properties (proxied from component):
     * @var string $search
     * @var int|null $groupFilter
     * @var float|null $minPrice
     * @var float|null $maxPrice
     * @var string $sortBy
     * @var string $sortDirection
     */
@endphp

<div class="container mx-auto px-4 py-8">

{{-- Header --}}
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">
            @if ($groupSlug)
                Items in Group: {{ $groupSlug }}
            @else
                Product Catalog
            @endif
        </h1>
        <p class="text-gray-600">Browse our collection of {{ $items->total() }} items</p>
    </div>

    {{-- Filters Section --}}
    <div class="bg-gradient-to-br from-orange-50 to-amber-50 shadow-sm rounded-lg p-6 mb-8 border border-orange-100">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Filters</h2>
            <button wire:click="clearFilters" class="text-sm text-orange-600 hover:text-orange-800 font-medium">
                Clear All
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Search --}}
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="search" wire:model.live.debounce.300ms="search"
                    class="w-full px-3 py-2 border border-orange-200 rounded-md focus:ring-orange-500 focus:border-orange-500 bg-white"
                    placeholder="Search items...">
            </div>

            {{-- Group Filter --}}
            <div>
                <label for="groupFilter" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select id="groupFilter" wire:model.live="groupFilter"
                    class="w-full px-3 py-2 border border-orange-200 rounded-md focus:ring-orange-500 focus:border-orange-500 bg-white">
                    <option value="">All Categories</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Min Price --}}
            <div>
                <label for="minPrice" class="block text-sm font-medium text-gray-700 mb-1">Min Price</label>
                <input type="number" id="minPrice" wire:model.live.debounce.300ms="minPrice"
                    class="w-full px-3 py-2 border border-orange-200 rounded-md focus:ring-orange-500 focus:border-orange-500 bg-white"
                    placeholder="$0" step="0.01">
            </div>

            {{-- Max Price --}}
            <div>
                <label for="maxPrice" class="block text-sm font-medium text-gray-700 mb-1">Max Price</label>
                <input type="number" id="maxPrice" wire:model.live.debounce.300ms="maxPrice"
                    class="w-full px-3 py-2 border border-orange-200 rounded-md focus:ring-orange-500 focus:border-orange-500 bg-white"
                    placeholder="$1000" step="0.01">
            </div>
        </div>

        {{-- Sorting --}}
        <div class="mt-4 flex items-center gap-4">
            <span class="text-sm font-medium text-gray-700">Sort by:</span>
            <button wire:click="sortBy('title')"
                class="text-sm {{ $sortBy === 'title' ? 'text-orange-600 font-semibold' : 'text-gray-600' }} hover:text-orange-800">
                Name {{ $sortBy === 'title' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
            </button>
            <button wire:click="sortBy('price')"
                class="text-sm {{ $sortBy === 'price' ? 'text-orange-600 font-semibold' : 'text-gray-600' }} hover:text-orange-800">
                Price {{ $sortBy === 'price' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
            </button>
            <button wire:click="sortBy('created_at')"
                class="text-sm {{ $sortBy === 'created_at' ? 'text-orange-600 font-semibold' : 'text-gray-600' }} hover:text-orange-800">
                Date {{ $sortBy === 'created_at' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
            </button>
        </div>
    </div>

    {{-- Items Grid --}}
    <div class="mb-8">
        @if($items->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($items as $item)
                    <a href="{{ route('item.show', $item->slug) }}" wire:navigate
                        class="group bg-white rounded-lg shadow-sm hover:shadow-xl hover:shadow-orange-100 transition-all duration-300 overflow-hidden border border-transparent hover:border-orange-200">
                        {{-- Item Image --}}
                        <div class="aspect-square w-full overflow-hidden bg-gradient-to-br from-orange-50 to-amber-50">
                            @if($item->image)
                                <img src="{{ $item->image }}"
                                    alt="{{ $item->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Item Details --}}
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-orange-600 line-clamp-2">
                                    {{ $item->title }}
                                </h3>
                            </div>

                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                {{ $item->description }}
                            </p>

                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-orange-600">
                                    ${{ number_format($item->price, 2) }}
                                </span>
                                <span class="text-xs px-2 py-1 bg-gradient-to-r from-orange-100 to-amber-100 text-orange-800 rounded-full font-medium">
                                    {{ $item->group->title ?? 'Uncategorized' }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-lg shadow-sm p-12 text-center border border-orange-100">
                <svg class="mx-auto h-12 w-12 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No items found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search terms.</p>
                <div class="mt-6">
                    <button wire:click="clearFilters" type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Clear Filters
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $items->links() }}
    </div>
</div>
