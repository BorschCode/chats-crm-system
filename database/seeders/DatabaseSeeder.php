<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 5 groups
        $groups = \App\Models\Group::factory(5)->create();

        // Create 10 items for each group
        $groups->each(function ($group) {
            \App\Models\Item::factory(10)->create([
                'group_id' => $group->id,
            ]);
        });

        // Create 5 items without a group
        \App\Models\Item::factory(5)->create([
            'group_id' => null,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
