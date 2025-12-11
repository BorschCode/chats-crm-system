<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg overflow-hidden rounded-lg border border-orange-100">
        <div class="px-6 py-8 bg-gradient-to-r from-orange-50 to-amber-50">
            <h3 class="text-3xl leading-6 font-bold text-gray-900">
                {{ $item->title }}
            </h3>
            <p class="mt-2 max-w-2xl text-sm text-gray-600">
                Product details and specifications.
            </p>
        </div>
        <div class="border-t border-orange-100">
            <dl>
                <div class="bg-orange-50/50 px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-700">
                        Price
                    </dt>
                    <dd class="mt-1 text-2xl font-bold text-orange-600 sm:mt-0 sm:col-span-2">
                        ${{ number_format($item->price, 2) }}
                    </dd>
                </div>
                <div class="bg-white px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-700">
                        Category
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <a href="{{ route('items.by.group', $item->group->slug ?? 'all') }}" wire:navigate
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-orange-100 to-amber-100 text-orange-800 hover:from-orange-200 hover:to-amber-200 transition-colors">
                            {{ $item->group->title ?? 'Uncategorized' }}
                        </a>
                    </dd>
                </div>
                <div class="bg-orange-50/50 px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-700">
                        Description
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $item->description }}
                    </dd>
                </div>
                @if ($item->image)
                    <div class="bg-white px-6 py-5">
                        <dt class="text-sm font-medium text-gray-700 mb-4">
                            Product Image
                        </dt>
                        <dd class="mt-1">
                            <img src="{{ $item->image }}" alt="{{ $item->title }}"
                                class="max-w-lg w-full h-auto rounded-lg shadow-lg border-2 border-orange-100">
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
        <div class="px-6 py-5 bg-gradient-to-r from-orange-50 to-amber-50 border-t border-orange-100">
            <a href="{{ route('home') }}" wire:navigate
                class="inline-flex items-center text-orange-600 hover:text-orange-800 font-medium transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Catalog
            </a>
        </div>
    </div>
</div>
