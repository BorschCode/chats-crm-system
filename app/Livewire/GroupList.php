<?php

namespace App\Livewire;

use App\Services\CatalogService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\View as Viewilluminate;
use Livewire\Component;

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
