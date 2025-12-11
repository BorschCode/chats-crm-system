<?php

namespace App\Livewire;

use App\Services\CatalogService;
use Livewire\Component;

class ItemList extends Component
{
    public ?string $groupSlug = null;

    protected CatalogService $catalogService;

    public function boot(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function mount(?string $groupSlug = null)
    {
        $this->groupSlug = $groupSlug;
    }

    public function render()
    {
        return view('livewire.item-list', [
            'items' => $this->catalogService->listItems($this->groupSlug),
            'groupSlug' => $this->groupSlug,
        ]);
    }
}
