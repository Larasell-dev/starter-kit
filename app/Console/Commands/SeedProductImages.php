<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Larasell\Larasell\Models\Product;
use Larasell\Larasell\Models\ProductImage;
use RuntimeException;

#[Signature('app:seed-product-images
    {--image= : URL or local path of the image to attach (required)}
    {--min=3 : Minimum number of images per product}
    {--max=12 : Maximum number of images per product}
    {--fresh : Detach and delete existing product images first}')]
#[Description('Attach 3 to 12 copies of an image to every product')]
class SeedProductImages extends Command
{
    public function handle(): int
    {
        $source = (string) $this->option('image');
        $min = (int) $this->option('min');
        $max = (int) $this->option('max');

        if ($source === '') {
            $this->components->error('The --image option is required.');

            return self::FAILURE;
        }

        if ($min < 1 || $max < $min) {
            $this->components->error('The image count range is invalid.');

            return self::FAILURE;
        }

        $tempFile = $this->download($source);

        $disk = Storage::disk(config('larasell.images.disk'));
        $products = Product::all();
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            if ($this->option('fresh')) {
                foreach ($product->images as $existing) {
                    $product->images()->detach($existing->id);

                    if ($existing->products()->doesntExist()) {
                        $disk->delete($existing->path);
                        $existing->delete();
                    }
                }
            }

            $count = rand($min, $max);

            for ($position = 0; $position < $count; $position++) {
                $path = $disk->putFile('products', $tempFile);
                $image = ProductImage::create(['path' => $path]);

                $product->images()->attach($image->id, ['position' => $position]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        if ($tempFile !== $source) {
            @unlink($tempFile);
        }

        $this->components->info(sprintf('Attached %d-%d images to %d products.', $min, $max, $products->count()));

        return self::SUCCESS;
    }

    private function download(string $source): string
    {
        if (is_file($source)) {
            return $source;
        }

        if (! str_starts_with($source, 'https://') && ! str_starts_with($source, 'http://')) {
            throw new RuntimeException(sprintf('The image "%s" is neither a local file nor a URL.', $source));
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'larasell-image');

        if ($tempFile === false || file_put_contents($tempFile, file_get_contents($source)) === false) {
            throw new RuntimeException(sprintf('Could not download the image from "%s".', $source));
        }

        return $tempFile;
    }
}