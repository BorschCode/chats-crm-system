<?php

namespace App\Livewire;

use App\Models\Group;
use App\Services\CatalogService;
use Livewire\Component;
use Livewire\WithPagination;

class ItemList extends Component
{
    use WithPagination;

    public ?string $groupSlug = null;

    // Filter properties
    public string $search = '';
    public ?int $groupFilter = null;
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public string $sortBy = 'title';
    public string $sortDirection = 'asc';

    protected CatalogService $catalogService;

    public function boot(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function mount(?string $groupSlug = null)
    {
        $this->groupSlug = $groupSlug;

        // Set group filter if coming from group page
        if ($groupSlug) {
            $group = Group::where('slug', $groupSlug)->first();
            if ($group) {
                $this->groupFilter = $group->id;
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGroupFilter()
    {
        $this->resetPage();
    }

    public function updatingMinPrice()
    {
        $this->resetPage();
    }

    public function updatingMaxPrice()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'groupFilter', 'minPrice', 'maxPrice']);
        $this->resetPage();
    }

    public function sortBy(string $field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.item-list', [
            'items' => $this->catalogService->listItemsPaginated(
                search: $this->search,
                groupId: $this->groupFilter,
                minPrice: $this->minPrice,
                maxPrice: $this->maxPrice,
                sortBy: $this->sortBy,
                sortDirection: $this->sortDirection
            ),
            'groups' => Group::all(),
            'groupSlug' => $this->groupSlug,
        ]);
    }
}
