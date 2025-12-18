<?php

namespace App\Livewire;

use App\Services\CatalogService;
use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\View as Viewilluminate;

class GroupList extends Component
{
    protected CatalogService $catalogService;

    public function boot(CatalogService $catalogService): void
    {
        $this->catalogService = $catalogService;
    }

    public function render(): Factory|View|Viewilluminate
    {
        return view('livewire.group-list', [
            'groups' => $this->catalogService->listGroups(),
        ]);
    }
}
