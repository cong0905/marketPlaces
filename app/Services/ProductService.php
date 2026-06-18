<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepo
    ) {}

    public function getActiveProducts(int $perPage = 12): LengthAwarePaginator
    {
        return $this->productRepo->getActiveProducts($perPage);
    }

    public function search(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        return $this->productRepo->search($filters, $perPage);
    }

    public function createProduct(array $data, array $images = []): Product
    {
        return DB::transaction(function () use ($data, $images) {
            $product = $this->productRepo->create($data);

            foreach ($images as $index => $image) {
                $this->storeImage($product, $image, $index === 0);
            }

            return $product->load('images');
        });
    }

    public function updateProduct(int $id, array $data, array $newImages = [], array $deleteImageIds = []): Product
    {
        return DB::transaction(function () use ($id, $data, $newImages, $deleteImageIds) {
            $product = $this->productRepo->findOrFail($id);

            // If quantity is updated to > 0 and the product was marked as SOLD, set it back to ACTIVE
            if (isset($data['quantity']) && $data['quantity'] > 0 && $product->status->value === 'sold') {
                $data['status'] = \App\Enums\ProductStatus::ACTIVE;
                $data['sold_at'] = null;
            }

            $this->productRepo->update($id, $data);
            $product = $this->productRepo->findOrFail($id);

            // Delete specified images
            if (!empty($deleteImageIds)) {
                $imagesToDelete = ProductImage::whereIn('id', $deleteImageIds)
                    ->where('product_id', $product->id)
                    ->get();

                foreach ($imagesToDelete as $img) {
                    Storage::disk('public')->delete([
                        str_replace(basename($img->path), 'original_' . basename($img->path), $img->path),
                        str_replace(basename($img->path), 'medium_' . basename($img->path), $img->path),
                        str_replace(basename($img->path), 'thumb_' . basename($img->path), $img->path),
                        $img->path
                    ]);
                    $img->delete();
                }
            }

            // Add new images
            foreach ($newImages as $image) {
                $this->storeImage($product, $image, false);
            }

            // Ensure there's a primary image
            if (!$product->images()->where('is_primary', true)->exists()) {
                $firstImage = $product->images()->first();
                $firstImage?->update(['is_primary' => true]);
            }

            return $product->fresh(['images']);
        });
    }

    protected function storeImage(Product $product, UploadedFile $file, bool $isPrimary = false): ProductImage
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);

        $filename = uniqid() . '.webp';
        $path = 'products/' . $product->id . '/';
        
        // Ensure directory exists
        Storage::disk('public')->makeDirectory($path);

        // Original (max 1200px)
        $original = clone $image;
        $original->scaleDown(1200);
        Storage::disk('public')->put($path . 'original_' . $filename, (string) $original->toWebp(80));

        // Medium (max 800px)
        $medium = clone $image;
        $medium->scaleDown(800);
        Storage::disk('public')->put($path . 'medium_' . $filename, (string) $medium->toWebp(80));

        // Thumbnail (max 300px)
        $thumbnail = clone $image;
        $thumbnail->scaleDown(300);
        Storage::disk('public')->put($path . 'thumb_' . $filename, (string) $thumbnail->toWebp(80));

        return $product->images()->create([
            'path' => $path . $filename,
            'is_primary' => $isPrimary,
            'sort_order' => $product->images()->count(),
        ]);
    }

    public function approve(int $id): void
    {
        $product = $this->productRepo->findOrFail($id);
        $product->approve();
    }

    public function reject(int $id, string $reason): void
    {
        $product = $this->productRepo->findOrFail($id);
        $product->reject($reason);
    }

    public function deleteProduct(int $id): void
    {
        $product = $this->productRepo->findOrFail($id);

        // Delete all images from storage
        foreach ($product->images as $image) {
            $basePath = str_replace('.webp', '', $image->path);
            Storage::disk('public')->delete([
                str_replace(basename($image->path), 'original_' . basename($image->path), $image->path),
                str_replace(basename($image->path), 'medium_' . basename($image->path), $image->path),
                str_replace(basename($image->path), 'thumb_' . basename($image->path), $image->path),
                $image->path // fallback if old format
            ]);
        }

        $product->delete();
    }
}
