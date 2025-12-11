<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg overflow-hidden rounded-lg border border-orange-100">
        <div class="px-6 py-5 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-orange-100">
            <h2 class="text-3xl font-bold text-gray-900">Product Categories</h2>
            <p class="mt-1 text-sm text-gray-600">Browse items by category</p>
        </div>
        <ul role="list" class="divide-y divide-orange-100">
            @forelse ($groups as $group)
                <li>
                    <a href="{{ route('items.by.group', $group->slug) }}" wire:navigate
                        class="block hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 transition-colors">
                        <div class="px-6 py-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-orange-400 to-amber-400 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-xl font-bold">{{ substr($group->title, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-lg font-semibold text-orange-600 hover:text-orange-800">
                                            {{ $group->title }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ Str::limit($group->description, 100) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <li class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <p class="mt-2 text-gray-500 font-medium">No categories found.</p>
                </li>
            @endforelse
        </ul>
    </div>
</div>
