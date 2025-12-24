<?php

namespace App\Console\Commands;

use App\Data\ProductCatalog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DownloadSeedImages extends Command
{
    protected $signature = 'images:download-seeds
        {--force : Overwrite existing images}
        {--interactive : Choose from multiple photo options}
        {--per-page=5 : Number of photos to fetch per query (1–15)}';

    protected $description = 'Download product seed images from Pexels into public/ for git-tracked seeding';

    private string $apiKey;

    public function handle(): int
    {
        $this->apiKey = config('services.pexels.key');

        if (empty($this->apiKey)) {
            $this->error('PEXELS_API_KEY is not set.');

            return self::FAILURE;
        }

        foreach (ProductCatalog::PRODUCTS as $category => $products) {
            $this->line("\n▶ {$category}");

            foreach ($products as $product) {
                $this->processProduct($category, $product);
                usleep(500_000); // respect Pexels rate limits
            }
        }

        $this->info("\nDone.");

        return self::SUCCESS;
    }

    private function processProduct(string $category, array $product): void
    {
        $title = $product['title'];
        $query = $product['search_query'] ?? $title;
        $slug = Str::slug($title);

        $relativePath = "images/seeds/{$category}/{$slug}.jpg";
        $fullPath = public_path($relativePath);

        if (file_exists($fullPath) && ! $this->option('force')) {
            $this->line("⏩ {$title}");

            return;
        }

        $photos = $this->searchPhotos($query);

        if (empty($photos)) {
            $this->warn("⚠ No results for {$title}");

            return;
        }

        $photo = $this->selectPhoto($photos, $title);
        if (! $photo) {
            return;
        }

        $this->saveImage($photo['src']['large'], $fullPath);
        $this->saveMetadata($category, $slug, $product, $photo);

        $this->info("✅ {$title}");
    }

    private function searchPhotos(string $query): array
    {
        $perPage = max(1, min(15, (int) $this->option('per-page')));

        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
        ])
            ->timeout(10)
            ->get('https://api.pexels.com/v1/search', [
                'query' => $query,
                'per_page' => $perPage,
                'orientation' => 'landscape',
            ]);

        if ($response->failed()) {
            $this->error("API error {$response->status()} for '{$query}'");

            logger()->error('Pexels API error', [
                'query' => $query,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        }

        return $response->json('photos', []);
    }

    private function saveImage(string $url, string $fullPath): void
    {
        $dir = dirname($fullPath);

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $image = Http::timeout(15)->get($url)->body();
        file_put_contents($fullPath, $image);
    }

    private function saveMetadata(
        string $category,
        string $slug,
        array $product,
        array $photo
    ): void {
        $metadataPath = public_path("images/seeds/{$category}/{$slug}.json");

        file_put_contents(
            $metadataPath,
            json_encode([
                'product' => $product['title'],
                'photographer' => $photo['photographer'],
                'photographer_url' => $photo['photographer_url'],
                'photo_url' => $photo['url'],
                'photo_id' => $photo['id'],
                'downloaded_at' => now()->toIso8601String(),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    private function selectPhoto(array $photos, string $title): ?array
    {
        if (! $this->option('interactive')) {
            return $photos[0];
        }

        $choices = collect($photos)->map(
            fn ($p, $i) => '#'.($i + 1)." {$p['photographer']} ({$p['id']})"
        )->push('Skip')->toArray();

        $selected = $this->choice("Choose image for {$title}", $choices);

        if ($selected === 'Skip') {
            return null;
        }

        $index = (int) filter_var($selected, FILTER_SANITIZE_NUMBER_INT) - 1;

        return $photos[$index] ?? null;
    }
}
