<?php

namespace App\Livewire;

use App\Services\CatalogService;
use Livewire\Component;

class GroupList extends Component
{
    protected CatalogService $catalogService;

    public function boot(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function render()
    {
        return view('livewire.group-list', [
            'groups' => $this->catalogService->listGroups(),
        ]);
    }
}
