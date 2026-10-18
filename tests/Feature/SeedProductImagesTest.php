<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Larasell\Larasell\Models\ProductImage;

test('product images are stored on the public disk', function () {
    Storage::fake('public');
    $this->artisan('app:seed-catalog')->assertSuccessful();
    $upload = UploadedFile::fake()->image('product.jpg', 40, 50);
    $image = $upload->getPathname();

    $this->artisan('app:seed-product-images', ['--image' => $image, '--min' => 1, '--max' => 1])->assertSuccessful();
    $this->artisan('app:seed-product-images', ['--image' => $image, '--min' => 1, '--max' => 1, '--fresh' => true])->assertSuccessful();

    $files = Storage::disk('public')->allFiles();
    expect($files)->toHaveCount(ProductImage::count())
        ->and($files)->each->toStartWith('larasell/products/')
        ->and(ProductImage::first()->url())->toContain('/storage/larasell/products/');
});
