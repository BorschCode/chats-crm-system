<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->words(3, true);

        // Random image ID between 1-1000 for variety
        $imageId = $this->faker->numberBetween(1, 1000);

        // Random dimensions for more variety (keeping aspect ratio reasonable)
        $width = $this->faker->randomElement([400, 500, 600, 640, 800]);
        $height = $this->faker->randomElement([300, 400, 480, 600]);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'image' => "https://picsum.photos/id/{$imageId}/{$width}/{$height}",
            'group_id' => null, // Will be set in the seeder
        ];
    }
}
