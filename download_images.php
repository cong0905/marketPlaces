<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductImage;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = Product::with('category')->get();

echo "Bắt đầu tải ảnh cho " . $products->count() . " sản phẩm...\n";

foreach ($products as $product) {
    // Chỉ tải ảnh nếu sản phẩm chưa có ảnh
    if ($product->images()->count() == 0) {
        $keyword = urlencode(Str::ascii($product->category->name ?? 'product'));
        // Sử dụng loremflickr để lấy ảnh random theo keyword
        $url = "https://loremflickr.com/800/800/{$keyword}";
        
        try {
            echo "Đang tải ảnh cho: {$product->title} (từ khóa: {$keyword})...\n";
            $imageContent = file_get_contents($url);
            
            if ($imageContent) {
                $filename = 'products/' . Str::random(40) . '.jpg';
                Storage::disk('public')->put($filename, $imageContent);
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $filename,
                    'is_primary' => true,
                    'order' => 0
                ]);
                echo "-> Đã lưu ảnh thành công!\n";
            }
        } catch (\Exception $e) {
            echo "-> Lỗi khi tải ảnh: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Bỏ qua: {$product->title} (đã có ảnh)\n";
    }
}

echo "\nHoàn tất!\n";
