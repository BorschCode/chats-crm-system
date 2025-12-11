<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Item;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CatalogService
{
    /**
     * List all groups.
     *
     * @return Collection<int, Group>
     */
    public function listGroups(): Collection
    {
        return Group::all();
    }

    /**
     * List all items, optionally filtered by group slug.
     *
     * @return Collection<int, Item>
     */
    public function listItems(?string $groupSlug = null): Collection
    {
        $query = Item::query()->with('group');

        if ($groupSlug) {
            $group = Group::where('slug', $groupSlug)->first();
            if ($group) {
                $query->where('group_id', $group->id);
            } else {
                // Return empty collection if group is not found
                return new Collection;
            }
        }

        return $query->get();
    }

    /**
     * List items with pagination and filters.
     */
    public function listItemsPaginated(
        ?string $search = null,
        ?int $groupId = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        string $sortBy = 'title',
        string $sortDirection = 'asc',
        int $perPage = 12
    ): LengthAwarePaginator {
        $query = Item::query()->with('group');

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Group filter
        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        // Price range filter
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        // Sorting
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get a single item by slug.
     */
    public function getItem(string $slug): ?Item
    {
        return Item::where('slug', $slug)->with('group')->first();
    }
}
