<?php

namespace Database\Seeders;

use App\Data\ProductCatalog;
use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ProductCatalog::CATEGORIES as $slug => $meta) {
            Group::firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $meta['title'],
                    'description' => $meta['description'],
                ]
            );
        }
    }
}
