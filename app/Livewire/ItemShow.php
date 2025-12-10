<?php

namespace App\Livewire;

use App\Models\Item;
use App\Services\CatalogService;
use Livewire\Component;

class ItemShow extends Component
{
    public Item $item;

    public function mount(string $slug, CatalogService $catalogService)
    {
        $item = $catalogService->getItem($slug);

        if (!$item) {
            abort(404);
        }

        $this->item = $item;
    }

    public function render()
    {
        return view('livewire.item-show');
    }
}
