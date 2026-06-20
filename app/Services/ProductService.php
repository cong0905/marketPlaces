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
                    if (str_starts_with($img->path, 'cloudinary://')) {
                        $publicId = str_replace('cloudinary://', '', $img->path);
                        cloudinary()->destroy($publicId);
                    } else {
                        Storage::disk('public')->delete([
                            str_replace(basename($img->path), 'original_' . basename($img->path), $img->path),
                            str_replace(basename($img->path), 'medium_' . basename($img->path), $img->path),
                            str_replace(basename($img->path), 'thumb_' . basename($img->path), $img->path),
                            $img->path
                        ]);
                    }
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
        $folder = 'marketPlace/products/' . $product->id;
        
        try {
            $response = cloudinary()->upload($file->getRealPath(), [
                'folder' => $folder,
            ]);
            
            $path = 'cloudinary://' . $response->getPublicId();
        } catch (\Throwable $e) {
            // Fallback to local storage if Cloudinary fails
            $path = 'products/' . $product->id . '/';
            Storage::disk('public')->makeDirectory($path);
            
            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = uniqid() . '.' . $extension;
            
            $file->storeAs($path, 'original_' . $filename, 'public');
            Storage::disk('public')->copy($path . 'original_' . $filename, $path . 'medium_' . $filename);
            Storage::disk('public')->copy($path . 'original_' . $filename, $path . 'thumb_' . $filename);
            
            $path = $path . $filename;
        }

        return $product->images()->create([
            'path' => $path,
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

        // Delete all images from storage/Cloudinary
        foreach ($product->images as $image) {
            if (str_starts_with($image->path, 'cloudinary://')) {
                $publicId = str_replace('cloudinary://', '', $image->path);
                cloudinary()->destroy($publicId);
            } else {
                Storage::disk('public')->delete([
                    str_replace(basename($image->path), 'original_' . basename($image->path), $image->path),
                    str_replace(basename($image->path), 'medium_' . basename($image->path), $image->path),
                    str_replace(basename($image->path), 'thumb_' . basename($image->path), $image->path),
                    $image->path // fallback if old format
                ]);
            }
        }

        $product->delete();
    }
}
