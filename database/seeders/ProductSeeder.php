<?php

namespace Database\Seeders;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $categories = Category::whereNotNull('parent_id')->get();

        if ($categories->isEmpty()) {
            $categories = Category::all();
        }

        $products = [
            ['title' => 'iPhone 14 Pro Max 256GB - Tím đậm', 'price' => 18500000, 'condition' => 90, 'brand' => 'Apple', 'model' => 'iPhone 14 Pro Max', 'desc' => 'Máy đẹp 99%, pin 92%, fullbox. Không trầy xước, không vào nước. Có bảo hành Apple đến tháng 12.', 'category_slug' => 'iphone'],
            ['title' => 'MacBook Air M2 2022 - Xanh midnight', 'price' => 22000000, 'condition' => 95, 'brand' => 'Apple', 'model' => 'MacBook Air M2', 'desc' => 'Máy mới mua 3 tháng, còn bảo hành chính hãng. Cấu hình 8GB/256GB SSD. Fullbox phụ kiện.', 'category_slug' => 'macbook'],
            ['title' => 'Samsung Galaxy S23 Ultra 512GB', 'price' => 15900000, 'condition' => 85, 'brand' => 'Samsung', 'model' => 'Galaxy S23 Ultra', 'desc' => 'Máy đẹp, có vết trầy nhẹ ở viền. Camera hoạt động tốt. Kèm ốp lưng và cường lực.', 'category_slug' => 'samsung'],
            ['title' => 'Xe Honda Vision 2023 - Trắng ngọc', 'price' => 32000000, 'condition' => 92, 'brand' => 'Honda', 'model' => 'Vision 2023', 'desc' => 'Xe chính chủ, mới đi 5000km. Bảo dưỡng định kỳ đầy đủ. Không đâm đụng.', 'category_slug' => 'xe-may'],
            ['title' => 'Bàn làm việc gỗ tự nhiên 120x60cm', 'price' => 2500000, 'condition' => 80, 'brand' => null, 'model' => null, 'desc' => 'Bàn gỗ thông tự nhiên, chân sắt sơn tĩnh điện. Đã dùng 1 năm, còn rất tốt.', 'category_slug' => 'noi-that'],
            ['title' => 'Đồng hồ Casio G-Shock GA-2100', 'price' => 1800000, 'condition' => 95, 'brand' => 'Casio', 'model' => 'GA-2100', 'desc' => 'Đồng hồ mới 99%, còn nguyên seal. Mua về chưa dùng mấy vì có đồng hồ khác rồi.', 'category_slug' => 'dong-ho'],
            ['title' => 'Canon EOS R50 kèm lens kit', 'price' => 16000000, 'condition' => 88, 'brand' => 'Canon', 'model' => 'EOS R50', 'desc' => 'Máy ảnh mirrorless, shutter count thấp ~2000 lần. Kèm lens RF-S 18-45mm. Fullbox.', 'category_slug' => 'may-anh'],
            ['title' => 'iPad Air 5 M1 64GB Wifi', 'price' => 10500000, 'condition' => 90, 'brand' => 'Apple', 'model' => 'iPad Air 5', 'desc' => 'Máy đẹp như mới, pin 95%. Có kèm Apple Pencil 2 và bao da.', 'category_slug' => 'may-tinh-bang'],
            ['title' => 'Áo khoác Uniqlo Ultra Light Down', 'price' => 600000, 'condition' => 70, 'brand' => 'Uniqlo', 'model' => null, 'desc' => 'Áo phao siêu nhẹ, size M. Đã giặt sạch, không rách hay hư hỏng. Màu đen.', 'category_slug' => 'quan-ao-nam'],
            ['title' => 'Bộ sách "Nhà Giả Kim" + "Đắc Nhân Tâm"', 'price' => 120000, 'condition' => 75, 'brand' => null, 'model' => null, 'desc' => 'Sách còn mới 80%, không ghi chú hay rách. Bán combo 2 cuốn.', 'category_slug' => 'sach'],
            ['title' => 'Loa Bluetooth JBL Flip 6', 'price' => 1500000, 'condition' => 85, 'brand' => 'JBL', 'model' => 'Flip 6', 'desc' => 'Loa chống nước, âm thanh rất tốt. Pin dùng được khoảng 10 tiếng. Có dây sạc.', 'category_slug' => 'do-dien-tu'],
            ['title' => 'Dell XPS 15 9520 - i7/16GB/512GB', 'price' => 25000000, 'condition' => 88, 'brand' => 'Dell', 'model' => 'XPS 15 9520', 'desc' => 'Laptop mỏng nhẹ, màn OLED 3.5K tuyệt đẹp. Phù hợp thiết kế đồ họa, lập trình.', 'category_slug' => 'dell'],
        ];

        $provinces = \Illuminate\Support\Facades\DB::table('provinces')->get();
        $districts = \Illuminate\Support\Facades\DB::table('districts')->get()->groupBy('province_id');

        foreach ($products as $i => $p) {
            $user = $users[$i % $users->count()];
            
            // Get correct category based on slug
            $category = Category::where('slug', $p['category_slug'])->first();
            if (!$category) {
                $category = Category::first();
            }

            $province = $provinces[$i % $provinces->count()];
            $provinceDistricts = $districts[$province->id] ?? collect();
            $districtId = $provinceDistricts->count() > 0 ? $provinceDistricts->random()->id : null;

            Product::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'title' => $p['title'],
                'description' => $p['desc'],
                'price' => $p['price'],
                'is_negotiable' => $i % 3 === 0,
                'condition_percent' => $p['condition'],
                'brand' => $p['brand'],
                'model' => $p['model'],
                'province_id' => $province->id,
                'district_id' => $districtId,
                'status' => ProductStatus::ACTIVE,
                'approved_at' => now(),
                'view_count' => rand(10, 500),
            ]);
        }
    }
}
