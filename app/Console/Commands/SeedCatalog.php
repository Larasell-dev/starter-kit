<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Larasell\Larasell\Enums\Visibility;
use Larasell\Larasell\Models\Category;
use Larasell\Larasell\Models\Product;
use Larasell\Larasell\Price;
use Larasell\Larasell\Translatable;

#[Signature('app:seed-catalog {--fresh : Delete existing categories and products before seeding}')]
#[Description('Seed three top-level categories and two dozen streetwear products')]
class SeedCatalog extends Command
{
    /**
     * @var array<int, array{name: string, slug: string, products: array<int, array{name: string, slug: string, description: string, price: int, compare_at: int|null}>}>
     */
    private const CATEGORIES = [
        [
            'name' => 'Tops',
            'slug' => 'tops',
            'products' => [
                ['name' => 'Heavyweight Box Tee', 'slug' => 'heavyweight-box-tee', 'description' => 'Boxy-cut tee in 320gsm organic cotton with ribbed collar.', 'price' => 4500, 'compare_at' => null],
                ['name' => 'Overdyed Graphic Tee', 'slug' => 'overdyed-graphic-tee', 'description' => 'Garment-dyed tee with hand-pulled back print.', 'price' => 3800, 'compare_at' => null],
                ['name' => 'Washed Hoodie', 'slug' => 'washed-hoodie', 'description' => 'Fleece-lined hoodie with enzyme wash and dropped shoulders.', 'price' => 8900, 'compare_at' => 11000],
                ['name' => 'Zip-Up Track Jacket', 'slug' => 'zip-up-track-jacket', 'description' => 'Retro track jacket in tricot nylon with contrast piping.', 'price' => 11500, 'compare_at' => null],
                ['name' => 'Mesh Long Sleeve', 'slug' => 'mesh-long-sleeve', 'description' => 'Breathable mesh long sleeve with tonal chest logo.', 'price' => 4200, 'compare_at' => null],
                ['name' => 'Cropped Rugby Polo', 'slug' => 'cropped-rugby-polo', 'description' => 'Cotton rugby polo with chunky collar stripes.', 'price' => 6800, 'compare_at' => null],
                ['name' => 'Flannel Overshirt', 'slug' => 'flannel-overshirt', 'description' => 'Brushed flannel overshirt cut for layering, boxy fit.', 'price' => 9500, 'compare_at' => null],
                ['name' => 'Sleeveless Layer Tank', 'slug' => 'sleeveless-layer-tank', 'description' => 'Heavy rib tank with raw-edge armholes.', 'price' => 3200, 'compare_at' => null],
            ],
        ],
        [
            'name' => 'Bottoms',
            'slug' => 'bottoms',
            'products' => [
                ['name' => 'Wide Leg Cargo Pants', 'slug' => 'wide-leg-cargo-pants', 'description' => 'Loose-fit cargos in washed ripstop with six pockets.', 'price' => 10500, 'compare_at' => null],
                ['name' => 'Baggy Denim Jeans', 'slug' => 'baggy-denim-jeans', 'description' => '14oz selvedge-inspired denim with a stacked, baggy leg.', 'price' => 9800, 'compare_at' => 12500],
                ['name' => 'Nylon Track Pants', 'slug' => 'nylon-track-pants', 'description' => 'Shiny tricot track pants with snap side seams.', 'price' => 7900, 'compare_at' => null],
                ['name' => 'Heavyweight Sweat Shorts', 'slug' => 'heavyweight-sweat-shorts', 'description' => 'Above-knee loopback shorts with elastic waist and drawcord.', 'price' => 5500, 'compare_at' => null],
                ['name' => 'Skate Carpenter Pants', 'slug' => 'skate-carpenter-pants', 'description' => 'Durable duck canvas carpenter pants, relaxed through the leg.', 'price' => 8900, 'compare_at' => null],
                ['name' => 'Plaid Baggy Trousers', 'slug' => 'plaid-baggy-trousers', 'description' => 'Wool-blend plaid trousers with a deep pleated leg.', 'price' => 11500, 'compare_at' => null],
                ['name' => 'Tech Running Shorts', 'slug' => 'tech-running-shorts', 'description' => 'Featherlight woven shorts with zip pocket and liner.', 'price' => 4900, 'compare_at' => null],
                ['name' => 'Corduroy Wide Pants', 'slug' => 'corduroy-wide-pants', 'description' => '8-wale corduroy pants with a straight wide leg.', 'price' => 8500, 'compare_at' => null],
            ],
        ],
        [
            'name' => 'Accessories',
            'slug' => 'accessories',
            'products' => [
                ['name' => 'Chunky Beanie', 'slug' => 'chunky-beanie', 'description' => 'Ribbed cuff beanie in lambswool blend.', 'price' => 2800, 'compare_at' => null],
                ['name' => 'Washed Baseball Cap', 'slug' => 'washed-baseball-cap', 'description' => 'Distressed six-panel cap with embroidered logo.', 'price' => 3500, 'compare_at' => null],
                ['name' => 'Canvas Belt', 'slug' => 'canvas-belt', 'description' => 'Heavy webbing belt with metal roller buckle.', 'price' => 2500, 'compare_at' => null],
                ['name' => 'Ribbed Crew Socks 3-Pack', 'slug' => 'ribbed-crew-socks-3-pack', 'description' => 'Terry-footbed crew socks in a three pack.', 'price' => 2200, 'compare_at' => null],
                ['name' => 'Crossbody Utility Bag', 'slug' => 'crossbody-utility-bag', 'description' => 'Cordura crossbody bag with webbing strap and zip stash.', 'price' => 4800, 'compare_at' => null],
                ['name' => 'Wool Scarf', 'slug' => 'wool-scarf', 'description' => 'Fringed lambswool scarf with woven label.', 'price' => 4200, 'compare_at' => null],
                ['name' => 'Knit Balaclava', 'slug' => 'knit-balaclava', 'description' => 'Stretch-rib balaclava, one size fits most.', 'price' => 3200, 'compare_at' => null],
                ['name' => 'Chain Wallet', 'slug' => 'chain-wallet', 'description' => 'Trucker-style leather wallet with a curb chain.', 'price' => 5900, 'compare_at' => 6800],
            ],
        ],
    ];

    public function handle(): int
    {
        if ($this->option('fresh')) {
            $this->info('Deleting existing catalog data...');

            Product::query()->delete();
            Category::query()->delete();
        }

        $createdProducts = 0;

        foreach (self::CATEGORIES as $categoryData) {
            $category = Category::firstOrCreate(
                ['slug->en' => $categoryData['slug']],
                [
                    'name' => Translatable::fromString($categoryData['name']),
                    'slug' => Translatable::fromString($categoryData['slug']),
                    'status' => Visibility::Visible,
                ],
            );

            $bar = $this->output->createProgressBar(count($categoryData['products']));
            $bar->start();

            foreach ($categoryData['products'] as $productData) {
                if ($this->createProduct($category, $productData)) {
                    $createdProducts++;
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine();

            $this->components->twoColumnDetail($categoryData['name'], sprintf('%d products', count($categoryData['products'])));
        }

        $this->components->info(sprintf('Seeded %d categories and %d products.', count(self::CATEGORIES), $createdProducts));

        return self::SUCCESS;
    }

    /**
     * Create a product in the given category, skipping it if it already exists.
     *
     * @param  array{name: string, slug: string, description: string, price: int, compare_at: int|null}  $productData
     */
    private function createProduct(Category $category, array $productData): bool
    {
        $product = Product::firstOrCreate(
            ['slug->en' => $productData['slug']],
            [
                'name' => Translatable::fromString($productData['name']),
                'slug' => Translatable::fromString($productData['slug']),
                'description' => Translatable::fromString($productData['description']),
                'price' => Price::of($productData['price']),
                'compare_at' => $productData['compare_at'] !== null ? Price::of($productData['compare_at']) : null,
                'tax_category' => 'standard',
                'stock' => rand(10, 100),
                'status' => Visibility::Visible,
            ],
        );

        if (! $product->wasRecentlyCreated) {
            return false;
        }

        $product->categories()->attach($category->id);

        return true;
    }
}
