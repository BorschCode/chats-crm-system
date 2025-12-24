<?php

namespace Database\Seeders;

use App\Data\ProductCatalog;
use App\Models\Group;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ProductCatalog::PRODUCTS as $categorySlug => $products) {
            $group = Group::where('slug', $categorySlug)->first();

            if (! $group) {
                throw new \RuntimeException("Group '{$categorySlug}' not found. Run GroupSeeder first.");
            }

            foreach ($products as $product) {
                $this->seedItem($group->id, $categorySlug, $product);
            }
        }
    }

    private function seedItem(int $groupId, string $categorySlug, array $product): void
    {
        $slug = Str::slug($product['title']);
        $imagePath = "/images/seeds/{$categorySlug}/{$slug}.jpg";

        if (! file_exists(public_path($imagePath))) {
            $imagePath = '/images/seeds/placeholder.jpg';
        }

        Item::firstOrCreate(
            ['slug' => $slug],
            [
                'title' => $product['title'],
                'description' => $product['description'],
                'price' => $product['price'],
                'image' => $imagePath,
                'group_id' => $groupId,
            ]
        );
    }
}
