<?php

namespace App\Livewire;

use App\Models\Item;
use App\Services\CatalogService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\View as Viewilluminate;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class ItemShow extends Component
{
    public Item $item;

    public function mount(string $slug, CatalogService $catalogService): void
    {
        $item = $catalogService->getItem($slug);

        if (! $item) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $this->item = $item;
    }

    public function render(): Factory|View|Viewilluminate
    {
        return view('livewire.item-show');
    }
}
