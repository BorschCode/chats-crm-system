<?php

namespace App\Livewire;

use App\Models\Item;
use App\Services\CatalogService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
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
        $appName = config('app.name');
        $title = $this->item->title.' | '.$appName;

        $description = $this->item->description
            ? Str::limit(strip_tags($this->item->description), 160)
            : null;

        $keywords = collect([
            $this->item->title,
            $this->item->group?->title,
        ])->filter()->join(', ');

        return view('livewire.item-show')
            ->layout('components.layouts.app', [
                'title' => $title,
                'description' => $description,
                'keywords' => $keywords ?: null,
                'canonical' => route('item.show', $this->item->slug),
                'image' => $this->item->image,
                'type' => 'product',
                'author' => $appName,
                'publisher' => $appName,
            ]);
    }
}
