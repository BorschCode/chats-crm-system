<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <h2 class="text-2xl font-bold p-4 border-b border-gray-200">Product Groups</h2>
    <ul role="list" class="divide-y divide-gray-200">
        @forelse ($groups as $group)
            <li>
                <a href="{{ route('items.by.group', $group->slug) }}" wire:navigate class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-medium text-indigo-600 truncate">{{ $group->title }}</p>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    {{ Str::limit($group->description, 100) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
        @empty
            <li class="p-4 text-gray-500">No groups found.</li>
        @endforelse
    </ul>
</div>
