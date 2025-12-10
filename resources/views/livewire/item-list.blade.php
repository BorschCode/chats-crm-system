<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <h2 class="text-2xl font-bold p-4 border-b border-gray-200">
        @if ($groupSlug)
            Items in Group: {{ $groupSlug }}
        @else
            All Items
        @endif
    </h2>
    <ul role="list" class="divide-y divide-gray-200">
        @forelse ($items as $item)
            <li>
                <a href="{{ route('item.show', $item->slug) }}" wire:navigate class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-medium text-gray-900 truncate">{{ $item->title }}</p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    ${{ $item->price }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    {{ Str::limit($item->description, 100) }}
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                <p>Group: {{ $item->group->title ?? 'Uncategorized' }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
        @empty
            <li class="p-4 text-gray-500">No items found.</li>
        @endforelse
    </ul>
</div>
