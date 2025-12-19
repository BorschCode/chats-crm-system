<?php

namespace App\Livewire;

use App\Models\Group;
use App\Services\CatalogService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\View as Viewilluminate;
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

    public function boot(CatalogService $catalogService): void
    {
        $this->catalogService = $catalogService;
    }

    public function mount(?string $groupSlug = null): void
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

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingGroupFilter(): void
    {
        $this->resetPage();
    }

    public function updatingMinPrice(): void
    {
        $this->resetPage();
    }

    public function updatingMaxPrice(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'groupFilter', 'minPrice', 'maxPrice']);
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function render(): Factory|View|Viewilluminate
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
