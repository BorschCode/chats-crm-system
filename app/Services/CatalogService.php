<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Item;
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
     * Get a single item by slug.
     */
    public function getItem(string $slug): ?Item
    {
        return Item::where('slug', $slug)->with('group')->first();
    }
}
