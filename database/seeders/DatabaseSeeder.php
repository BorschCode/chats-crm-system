<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create categories with realistic catalog items
        $this->createElectronicsCategory();
        $this->createClothingCategory();
        $this->createHomeGardenCategory();
        $this->createSportsCategory();
        $this->createBooksMediaCategory();
        $this->createFoodBeveragesCategory();
    }

    private function createElectronicsCategory(): void
    {
        $electronics = Group::create([
            'title' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Discover the latest in consumer electronics, from smartphones to laptops and smart home devices.',
        ]);

        $items = [
            ['title' => 'iPhone 15 Pro', 'price' => 999.00, 'description' => 'Latest iPhone with A17 Pro chip, titanium design, and advanced camera system'],
            ['title' => 'Samsung Galaxy S24', 'price' => 899.00, 'description' => 'Flagship Android smartphone with AI features and stunning display'],
            ['title' => 'MacBook Air M3', 'price' => 1299.00, 'description' => '13-inch laptop with M3 chip, all-day battery life, and lightweight design'],
            ['title' => 'Dell XPS 15', 'price' => 1499.00, 'description' => 'Premium laptop with InfinityEdge display and powerful performance'],
            ['title' => 'iPad Pro 12.9"', 'price' => 1099.00, 'description' => 'Professional tablet with M2 chip and Liquid Retina XDR display'],
            ['title' => 'Sony WH-1000XM5', 'price' => 399.00, 'description' => 'Industry-leading noise canceling headphones with premium sound quality'],
            ['title' => 'AirPods Pro 2', 'price' => 249.00, 'description' => 'True wireless earbuds with active noise cancellation and spatial audio'],
            ['title' => 'Apple Watch Series 9', 'price' => 429.00, 'description' => 'Advanced health and fitness smartwatch with always-on Retina display'],
            ['title' => 'Amazon Echo Dot', 'price' => 49.99, 'description' => 'Compact smart speaker with Alexa voice control'],
            ['title' => 'Google Nest Hub', 'price' => 99.99, 'description' => 'Smart display for your home with Google Assistant'],
        ];

        foreach ($items as $item) {
            Item::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'description' => $item['description'],
                'price' => $item['price'],
                'image' => 'https://picsum.photos/600/400?random='.rand(1, 1000),
                'group_id' => $electronics->id,
            ]);
        }
    }

    private function createClothingCategory(): void
    {
        $clothing = Group::create([
            'title' => 'Clothing & Fashion',
            'slug' => 'clothing-fashion',
            'description' => 'Trendy apparel and accessories for men, women, and children.',
        ]);

        $items = [
            ['title' => 'Levi\'s 501 Original Jeans', 'price' => 89.99, 'description' => 'Classic straight fit jeans with authentic styling and comfort'],
            ['title' => 'Nike Air Max Sneakers', 'price' => 129.99, 'description' => 'Iconic running shoes with visible Air cushioning'],
            ['title' => 'Adidas Ultraboost', 'price' => 179.99, 'description' => 'Premium running shoes with responsive Boost cushioning'],
            ['title' => 'North Face Puffer Jacket', 'price' => 249.00, 'description' => 'Insulated winter jacket with water-resistant finish'],
            ['title' => 'Ray-Ban Aviator Sunglasses', 'price' => 159.00, 'description' => 'Classic aviator sunglasses with UV protection'],
            ['title' => 'Tommy Hilfiger Polo Shirt', 'price' => 69.99, 'description' => 'Classic fit polo shirt in premium cotton'],
            ['title' => 'H&M Cotton T-Shirt Pack', 'price' => 29.99, 'description' => 'Set of 3 essential cotton t-shirts in various colors'],
            ['title' => 'Zara Leather Jacket', 'price' => 199.00, 'description' => 'Genuine leather jacket with modern slim fit'],
            ['title' => 'Calvin Klein Boxer Briefs', 'price' => 39.99, 'description' => 'Comfortable cotton stretch underwear 3-pack'],
            ['title' => 'Timberland Boots', 'price' => 189.00, 'description' => 'Durable waterproof boots with premium leather'],
        ];

        foreach ($items as $item) {
            Item::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'description' => $item['description'],
                'price' => $item['price'],
                'image' => 'https://picsum.photos/600/400?random='.rand(1, 1000),
                'group_id' => $clothing->id,
            ]);
        }
    }

    private function createHomeGardenCategory(): void
    {
        $homeGarden = Group::create([
            'title' => 'Home & Garden',
            'slug' => 'home-garden',
            'description' => 'Everything you need for your home, from furniture to garden tools.',
        ]);

        $items = [
            ['title' => 'Dyson V15 Vacuum', 'price' => 649.00, 'description' => 'Cordless vacuum with laser detection and powerful suction'],
            ['title' => 'KitchenAid Stand Mixer', 'price' => 449.99, 'description' => 'Professional 5-quart mixer with 10 speeds and attachments'],
            ['title' => 'Instant Pot Duo', 'price' => 99.99, 'description' => '7-in-1 electric pressure cooker for quick meals'],
            ['title' => 'Nespresso Coffee Machine', 'price' => 179.00, 'description' => 'Single-serve coffee maker with milk frother'],
            ['title' => 'Philips Hue Smart Bulbs', 'price' => 49.99, 'description' => 'Color-changing LED bulbs with app control'],
            ['title' => 'iRobot Roomba Vacuum', 'price' => 399.00, 'description' => 'Robot vacuum with smart mapping and auto-charging'],
            ['title' => 'Weber Gas Grill', 'price' => 549.00, 'description' => '3-burner propane grill with side burner and storage'],
            ['title' => 'Casper Memory Foam Mattress', 'price' => 799.00, 'description' => 'Queen size mattress with pressure relief and cooling'],
            ['title' => 'Cuisinart Food Processor', 'price' => 149.99, 'description' => '14-cup food processor with multiple attachments'],
            ['title' => 'Black+Decker Drill Set', 'price' => 79.99, 'description' => '20V cordless drill with bits and carrying case'],
        ];

        foreach ($items as $item) {
            Item::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'description' => $item['description'],
                'price' => $item['price'],
                'image' => 'https://picsum.photos/600/400?random='.rand(1, 1000),
                'group_id' => $homeGarden->id,
            ]);
        }
    }

    private function createSportsCategory(): void
    {
        $sports = Group::create([
            'title' => 'Sports & Outdoors',
            'slug' => 'sports-outdoors',
            'description' => 'Gear up for your favorite sports and outdoor activities.',
        ]);

        $items = [
            ['title' => 'Yeti Rambler Tumbler', 'price' => 34.99, 'description' => 'Insulated stainless steel tumbler keeps drinks hot or cold'],
            ['title' => 'Fitbit Charge 6', 'price' => 159.99, 'description' => 'Advanced fitness tracker with heart rate monitoring'],
            ['title' => 'Coleman Camping Tent', 'price' => 129.00, 'description' => '4-person dome tent with weatherproof design'],
            ['title' => 'Wilson Basketball', 'price' => 29.99, 'description' => 'Official size basketball with superior grip'],
            ['title' => 'Spalding Baseball Glove', 'price' => 49.99, 'description' => 'Premium leather glove for infield play'],
            ['title' => 'Trek Mountain Bike', 'price' => 899.00, 'description' => '29" hardtail mountain bike with disc brakes'],
            ['title' => 'Yoga Mat Premium', 'price' => 39.99, 'description' => 'Extra thick non-slip yoga mat with carrying strap'],
            ['title' => 'Bowflex Adjustable Dumbbells', 'price' => 349.00, 'description' => 'Space-saving dumbbells with quick weight adjustment'],
            ['title' => 'Garmin GPS Watch', 'price' => 299.00, 'description' => 'Multisport GPS watch with advanced training features'],
            ['title' => 'Hydro Flask Water Bottle', 'price' => 44.95, 'description' => '32oz insulated water bottle keeps drinks cold 24 hours'],
        ];

        foreach ($items as $item) {
            Item::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'description' => $item['description'],
                'price' => $item['price'],
                'image' => 'https://picsum.photos/600/400?random='.rand(1, 1000),
                'group_id' => $sports->id,
            ]);
        }
    }

    private function createBooksMediaCategory(): void
    {
        $booksMedia = Group::create([
            'title' => 'Books & Media',
            'slug' => 'books-media',
            'description' => 'Books, movies, music, and more for entertainment and learning.',
        ]);

        $items = [
            ['title' => 'Atomic Habits by James Clear', 'price' => 16.99, 'description' => 'Bestselling book on building good habits and breaking bad ones'],
            ['title' => 'The Midnight Library', 'price' => 14.99, 'description' => 'Fiction novel about infinite possibilities and second chances'],
            ['title' => 'Dune: Complete Series Box Set', 'price' => 59.99, 'description' => 'Classic science fiction series in deluxe hardcover edition'],
            ['title' => 'National Geographic Subscription', 'price' => 39.00, 'description' => '12-month magazine subscription with digital access'],
            ['title' => 'Kindle Paperwhite', 'price' => 139.99, 'description' => 'Waterproof e-reader with adjustable warm light'],
            ['title' => 'PlayStation 5', 'price' => 499.99, 'description' => 'Next-gen gaming console with ultra-high speed SSD'],
            ['title' => 'Nintendo Switch OLED', 'price' => 349.99, 'description' => 'Handheld gaming console with vivid OLED screen'],
            ['title' => 'Bose SoundLink Speaker', 'price' => 129.00, 'description' => 'Portable Bluetooth speaker with 12-hour battery'],
            ['title' => 'Logitech Webcam', 'price' => 79.99, 'description' => '1080p HD webcam with auto-focus and stereo audio'],
            ['title' => 'Marvel Phase 4 Collection', 'price' => 149.99, 'description' => 'Complete Blu-ray collection of MCU Phase 4 films'],
        ];

        foreach ($items as $item) {
            Item::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'description' => $item['description'],
                'price' => $item['price'],
                'image' => 'https://picsum.photos/600/400?random='.rand(1, 1000),
                'group_id' => $booksMedia->id,
            ]);
        }
    }

    private function createFoodBeveragesCategory(): void
    {
        $foodBeverages = Group::create([
            'title' => 'Food & Beverages',
            'slug' => 'food-beverages',
            'description' => 'Gourmet foods, snacks, and beverages delivered to your door.',
        ]);

        $items = [
            ['title' => 'Starbucks Pike Place Coffee', 'price' => 12.99, 'description' => 'Medium roast whole bean coffee, 1lb bag'],
            ['title' => 'Lavazza Espresso Italiano', 'price' => 15.99, 'description' => 'Premium Italian espresso beans with rich flavor'],
            ['title' => 'Ghirardelli Chocolate Squares', 'price' => 9.99, 'description' => 'Assorted premium chocolate squares gift box'],
            ['title' => 'KIND Protein Bars Variety', 'price' => 19.99, 'description' => 'Healthy protein bars 12-count variety pack'],
            ['title' => 'Twinings English Breakfast Tea', 'price' => 7.99, 'description' => 'Classic black tea, 100 tea bags'],
            ['title' => 'Haribo Gummy Bears', 'price' => 4.99, 'description' => 'Original gummy bears 5lb bulk bag'],
            ['title' => 'Coca-Cola Classic 24-Pack', 'price' => 8.99, 'description' => '24 cans of refreshing Coca-Cola'],
            ['title' => 'Red Bull Energy Drink 12-Pack', 'price' => 21.99, 'description' => '12 cans of energy drink with caffeine'],
            ['title' => 'Perrier Sparkling Water', 'price' => 14.99, 'description' => 'Natural sparkling mineral water 24-pack'],
            ['title' => 'Blue Diamond Almonds', 'price' => 11.99, 'description' => 'Roasted salted almonds 16oz container'],
        ];

        foreach ($items as $item) {
            Item::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'description' => $item['description'],
                'price' => $item['price'],
                'image' => 'https://picsum.photos/600/400?random='.rand(1, 1000),
                'group_id' => $foodBeverages->id,
            ]);
        }
    }
}
