<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TelegramCatalogController extends Controller
{
    public function __construct(
        protected CatalogService $catalogService
    ) {}

    /**
     * Get all product groups
     */
    public function groups(Request $request): JsonResponse
    {
        $groups = $this->catalogService->listGroups();

        return response()->json([
            'success' => true,
            'user' => $request->input('telegramUser'),
            'groups' => $groups->map(fn ($group) => [
                'id' => $group->id,
                'slug' => $group->slug,
                'title' => $group->title,
                'description' => $group->description,
            ]),
        ]);
    }

    /**
     * Get items (optionally filtered by group)
     */
    public function items(Request $request, ?string $groupSlug = null): JsonResponse
    {
        $items = $this->catalogService->listItems($groupSlug);

        return response()->json([
            'success' => true,
            'group_slug' => $groupSlug,
            'items' => $items->map(fn ($item) => [
                'id' => $item->id,
                'slug' => $item->slug,
                'title' => $item->title,
                'description' => $item->description,
                'price' => $item->price,
                'image' => $item->image,
                'group' => $item->group ? [
                    'slug' => $item->group->slug,
                    'title' => $item->group->title,
                ] : null,
            ]),
        ]);
    }

    /**
     * Get single item by slug
     */
    public function item(Request $request, string $slug): JsonResponse
    {
        $item = $this->catalogService->getItem($slug);

        if (! $item) {
            return response()->json([
                'success' => false,
                'error' => 'Item not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'item' => [
                'id' => $item->id,
                'slug' => $item->slug,
                'title' => $item->title,
                'description' => $item->description,
                'price' => $item->price,
                'image' => $item->image,
                'group' => $item->group ? [
                    'id' => $item->group->id,
                    'slug' => $item->group->slug,
                    'title' => $item->group->title,
                ] : null,
            ],
        ]);
    }
}
