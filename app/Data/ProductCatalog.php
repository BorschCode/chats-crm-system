<?php

namespace App\Data;

class ProductCatalog
{
    /**
     * Complete product catalog for seeding and image downloads
     * Format: category => [items with title, price, description, search_query (optional)]
     */
    public const PRODUCTS = [
        'electronics' => [
            ['title' => 'iPhone 15 Pro', 'price' => 999.00, 'description' => 'Latest iPhone with A17 Pro chip, titanium design, and advanced camera system'],
            ['title' => 'Samsung Galaxy S24', 'price' => 899.00, 'description' => 'Flagship Android smartphone with AI features and stunning display'],
            ['title' => 'MacBook Air M3', 'price' => 1299.00, 'description' => '13-inch laptop with M3 chip, all-day battery life, and lightweight design'],
            ['title' => 'Dell XPS 15', 'price' => 1499.00, 'description' => 'Premium laptop with InfinityEdge display and powerful performance'],
            ['title' => 'iPad Pro 12.9"', 'price' => 1099.00, 'description' => 'Professional tablet with M2 chip and Liquid Retina XDR display', 'search_query' => 'iPad Pro tablet'],
            ['title' => 'Sony WH-1000XM5', 'price' => 399.00, 'description' => 'Industry-leading noise canceling headphones with premium sound quality', 'search_query' => 'Sony headphones wireless'],
            ['title' => 'AirPods Pro 2', 'price' => 249.00, 'description' => 'True wireless earbuds with active noise cancellation and spatial audio', 'search_query' => 'AirPods wireless earbuds'],
            ['title' => 'Apple Watch Series 9', 'price' => 429.00, 'description' => 'Advanced health and fitness smartwatch with always-on Retina display'],
            ['title' => 'Amazon Echo Dot', 'price' => 49.99, 'description' => 'Compact smart speaker with Alexa voice control'],
            ['title' => 'Google Nest Hub', 'price' => 99.99, 'description' => 'Smart display for your home with Google Assistant'],
        ],

        'clothing' => [
            ['title' => 'Levi\'s 501 Original Jeans', 'price' => 89.99, 'description' => 'Classic straight fit jeans with authentic styling and comfort', 'search_query' => 'blue denim jeans'],
            ['title' => 'Nike Air Max Sneakers', 'price' => 129.99, 'description' => 'Iconic running shoes with visible Air cushioning'],
            ['title' => 'Adidas Ultraboost', 'price' => 179.99, 'description' => 'Premium running shoes with responsive Boost cushioning', 'search_query' => 'Adidas running shoes'],
            ['title' => 'North Face Puffer Jacket', 'price' => 249.00, 'description' => 'Insulated winter jacket with water-resistant finish', 'search_query' => 'winter puffer jacket'],
            ['title' => 'Ray-Ban Aviator Sunglasses', 'price' => 159.00, 'description' => 'Classic aviator sunglasses with UV protection'],
            ['title' => 'Tommy Hilfiger Polo Shirt', 'price' => 69.99, 'description' => 'Classic fit polo shirt in premium cotton', 'search_query' => 'polo shirt casual'],
            ['title' => 'H&M Cotton T-Shirt Pack', 'price' => 29.99, 'description' => 'Set of 3 essential cotton t-shirts in various colors', 'search_query' => 'white t-shirt cotton'],
            ['title' => 'Zara Leather Jacket', 'price' => 199.00, 'description' => 'Genuine leather jacket with modern slim fit', 'search_query' => 'black leather jacket'],
            ['title' => 'Calvin Klein Boxer Briefs', 'price' => 39.99, 'description' => 'Comfortable cotton stretch underwear 3-pack', 'search_query' => 'mens underwear'],
            ['title' => 'Timberland Boots', 'price' => 189.00, 'description' => 'Durable waterproof boots with premium leather', 'search_query' => 'leather boots brown'],
        ],

        'home-garden' => [
            ['title' => 'Dyson V15 Vacuum', 'price' => 649.00, 'description' => 'Cordless vacuum with laser detection and powerful suction', 'search_query' => 'Dyson vacuum cleaner'],
            ['title' => 'KitchenAid Stand Mixer', 'price' => 449.99, 'description' => 'Professional 5-quart mixer with 10 speeds and attachments'],
            ['title' => 'Instant Pot Duo', 'price' => 99.99, 'description' => '7-in-1 electric pressure cooker for quick meals', 'search_query' => 'pressure cooker appliance'],
            ['title' => 'Nespresso Coffee Machine', 'price' => 179.00, 'description' => 'Single-serve coffee maker with milk frother'],
            ['title' => 'Philips Hue Smart Bulbs', 'price' => 49.99, 'description' => 'Color-changing LED bulbs with app control', 'search_query' => 'smart light bulbs'],
            ['title' => 'iRobot Roomba Vacuum', 'price' => 399.00, 'description' => 'Robot vacuum with smart mapping and auto-charging', 'search_query' => 'robot vacuum cleaner'],
            ['title' => 'Weber Gas Grill', 'price' => 549.00, 'description' => '3-burner propane grill with side burner and storage', 'search_query' => 'BBQ gas grill'],
            ['title' => 'Casper Memory Foam Mattress', 'price' => 799.00, 'description' => 'Queen size mattress with pressure relief and cooling', 'search_query' => 'white mattress bed'],
            ['title' => 'Cuisinart Food Processor', 'price' => 149.99, 'description' => '14-cup food processor with multiple attachments'],
            ['title' => 'Black+Decker Drill Set', 'price' => 79.99, 'description' => '20V cordless drill with bits and carrying case', 'search_query' => 'power drill tools'],
        ],

        'sports' => [
            ['title' => 'Yeti Rambler Tumbler', 'price' => 34.99, 'description' => 'Insulated stainless steel tumbler keeps drinks hot or cold', 'search_query' => 'stainless steel water bottle'],
            ['title' => 'Fitbit Charge 6', 'price' => 159.99, 'description' => 'Advanced fitness tracker with heart rate monitoring'],
            ['title' => 'Coleman Camping Tent', 'price' => 129.00, 'description' => '4-person dome tent with weatherproof design', 'search_query' => 'camping tent outdoor'],
            ['title' => 'Wilson Basketball', 'price' => 29.99, 'description' => 'Official size basketball with superior grip'],
            ['title' => 'Spalding Baseball Glove', 'price' => 49.99, 'description' => 'Premium leather glove for infield play', 'search_query' => 'baseball glove leather'],
            ['title' => 'Trek Mountain Bike', 'price' => 899.00, 'description' => '29" hardtail mountain bike with disc brakes', 'search_query' => 'mountain bike trail'],
            ['title' => 'Yoga Mat Premium', 'price' => 39.99, 'description' => 'Extra thick non-slip yoga mat with carrying strap', 'search_query' => 'yoga mat exercise'],
            ['title' => 'Bowflex Adjustable Dumbbells', 'price' => 349.00, 'description' => 'Space-saving dumbbells with quick weight adjustment', 'search_query' => 'dumbbells weights gym'],
            ['title' => 'Garmin GPS Watch', 'price' => 299.00, 'description' => 'Multisport GPS watch with advanced training features', 'search_query' => 'Garmin smartwatch sports'],
            ['title' => 'Hydro Flask Water Bottle', 'price' => 44.95, 'description' => '32oz insulated water bottle keeps drinks cold 24 hours', 'search_query' => 'water bottle insulated'],
        ],

        'books' => [
            ['title' => 'Atomic Habits by James Clear', 'price' => 16.99, 'description' => 'Bestselling book on building good habits and breaking bad ones', 'search_query' => 'hardcover book'],
            ['title' => 'The Midnight Library', 'price' => 14.99, 'description' => 'Fiction novel about infinite possibilities and second chances', 'search_query' => 'novel book fiction'],
            ['title' => 'Dune: Complete Series Box Set', 'price' => 59.99, 'description' => 'Classic science fiction series in deluxe hardcover edition', 'search_query' => 'book collection stack'],
            ['title' => 'National Geographic Subscription', 'price' => 39.00, 'description' => '12-month magazine subscription with digital access', 'search_query' => 'magazine reading'],
            ['title' => 'Kindle Paperwhite', 'price' => 139.99, 'description' => 'Waterproof e-reader with adjustable warm light', 'search_query' => 'e-reader device'],
            ['title' => 'PlayStation 5', 'price' => 499.99, 'description' => 'Next-gen gaming console with ultra-high speed SSD', 'search_query' => 'PS5 gaming console'],
            ['title' => 'Nintendo Switch OLED', 'price' => 349.99, 'description' => 'Handheld gaming console with vivid OLED screen'],
            ['title' => 'Bose SoundLink Speaker', 'price' => 129.00, 'description' => 'Portable Bluetooth speaker with 12-hour battery', 'search_query' => 'Bluetooth speaker portable'],
            ['title' => 'Logitech Webcam', 'price' => 79.99, 'description' => '1080p HD webcam with auto-focus and stereo audio', 'search_query' => 'webcam computer'],
            ['title' => 'Marvel Phase 4 Collection', 'price' => 149.99, 'description' => 'Complete Blu-ray collection of MCU Phase 4 films', 'search_query' => 'DVD movies collection'],
        ],

        'food' => [
            ['title' => 'Starbucks Pike Place Coffee', 'price' => 12.99, 'description' => 'Medium roast whole bean coffee, 1lb bag', 'search_query' => 'coffee beans bag'],
            ['title' => 'Lavazza Espresso Italiano', 'price' => 15.99, 'description' => 'Premium Italian espresso beans with rich flavor', 'search_query' => 'espresso coffee beans'],
            ['title' => 'Ghirardelli Chocolate Squares', 'price' => 9.99, 'description' => 'Assorted premium chocolate squares gift box', 'search_query' => 'chocolate bar dark'],
            ['title' => 'KIND Protein Bars Variety', 'price' => 19.99, 'description' => 'Healthy protein bars 12-count variety pack', 'search_query' => 'protein bars healthy'],
            ['title' => 'Twinings English Breakfast Tea', 'price' => 7.99, 'description' => 'Classic black tea, 100 tea bags', 'search_query' => 'tea box bags'],
            ['title' => 'Haribo Gummy Bears', 'price' => 4.99, 'description' => 'Original gummy bears 5lb bulk bag', 'search_query' => 'gummy candy bears'],
            ['title' => 'Coca-Cola Classic 24-Pack', 'price' => 8.99, 'description' => '24 cans of refreshing Coca-Cola', 'search_query' => 'soda cans'],
            ['title' => 'Red Bull Energy Drink 12-Pack', 'price' => 21.99, 'description' => '12 cans of energy drink with caffeine', 'search_query' => 'energy drink can'],
            ['title' => 'Perrier Sparkling Water', 'price' => 14.99, 'description' => 'Natural sparkling mineral water 24-pack', 'search_query' => 'sparkling water bottle'],
            ['title' => 'Blue Diamond Almonds', 'price' => 11.99, 'description' => 'Roasted salted almonds 16oz container', 'search_query' => 'almonds nuts bowl'],
        ],
    ];

    /**
     * Category metadata for group creation
     */
    public const CATEGORIES = [
        'electronics' => [
            'title' => 'Electronics',
            'description' => 'Discover the latest in consumer electronics, from smartphones to laptops and smart home devices.',
        ],
        'clothing' => [
            'title' => 'Clothing & Fashion',
            'description' => 'Trendy apparel and accessories for men, women, and children.',
        ],
        'home-garden' => [
            'title' => 'Home & Garden',
            'description' => 'Everything you need for your home, from furniture to garden tools.',
        ],
        'sports' => [
            'title' => 'Sports & Outdoors',
            'description' => 'Gear up for your favorite sports and outdoor activities.',
        ],
        'books' => [
            'title' => 'Books & Media',
            'description' => 'Books, movies, music, and more for entertainment and learning.',
        ],
        'food' => [
            'title' => 'Food & Beverages',
            'description' => 'Gourmet foods, snacks, and beverages delivered to your door.',
        ],
    ];
}
