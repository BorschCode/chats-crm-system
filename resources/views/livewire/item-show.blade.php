<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-2xl leading-6 font-medium text-gray-900">
            {{ $item->title }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Product details and specifications.
        </p>
    </div>
    <div class="border-t border-gray-200">
        <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Price
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    ${{ $item->price }}
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Group
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <a href="{{ route('items.by.group', $item->group->slug ?? 'all') }}" wire:navigate class="text-indigo-600 hover:text-indigo-900">
                        {{ $item->group->title ?? 'Uncategorized' }}
                    </a>
                </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Description
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $item->description }}
                </dd>
            </div>
            @if ($item->image)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Image
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <img src="{{ $item->image }}" alt="{{ $item->title }}" class="max-w-xs h-auto rounded-lg shadow-lg">
                    </dd>
                </div>
            @endif
        </dl>
    </div>
    <div class="px-4 py-4 sm:px-6">
        <a href="{{ route('home') }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 font-medium">
            &larr; Back to Catalog
        </a>
    </div>
</div>
