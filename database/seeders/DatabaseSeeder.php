<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ─────────────────────────────────────
        User::create([
            'name' => 'Admin',
            'email' => 'admin@marketplace.test',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
            'email_verified_at' => now(),
            'phone' => '0123456789',
        ]);

        // ── Test Users ─────────────────────────────────────
        $users = [
            ['name' => 'Nguyễn Văn A', 'email' => 'user1@test.com', 'phone' => '0987654321', 'location_province' => 'Hà Nội'],
            ['name' => 'Trần Thị B', 'email' => 'user2@test.com', 'phone' => '0912345678', 'location_province' => 'TP Hồ Chí Minh'],
            ['name' => 'Lê Văn C', 'email' => 'user3@test.com', 'phone' => '0901234567', 'location_province' => 'Đà Nẵng'],
        ];

        foreach ($users as $u) {
            User::create(array_merge($u, [
                'password' => Hash::make('password'),
                'role' => UserRole::USER,
                'email_verified_at' => now(),
            ]));
        }

        // ── Categories ─────────────────────────────────────
        $categories = [
            ['name' => 'Điện thoại', 'slug' => 'dien-thoai', 'icon' => '📱', 'sort_order' => 1, 'children' => [
                ['name' => 'iPhone', 'slug' => 'iphone'],
                ['name' => 'Samsung', 'slug' => 'samsung'],
                ['name' => 'Xiaomi', 'slug' => 'xiaomi'],
                ['name' => 'OPPO', 'slug' => 'oppo'],
            ]],
            ['name' => 'Laptop', 'slug' => 'laptop', 'icon' => '💻', 'sort_order' => 2, 'children' => [
                ['name' => 'MacBook', 'slug' => 'macbook'],
                ['name' => 'Dell', 'slug' => 'dell'],
                ['name' => 'Lenovo', 'slug' => 'lenovo'],
                ['name' => 'Asus', 'slug' => 'asus'],
            ]],
            ['name' => 'Máy tính bảng', 'slug' => 'may-tinh-bang', 'icon' => '📋', 'sort_order' => 3],
            ['name' => 'Máy ảnh', 'slug' => 'may-anh', 'icon' => '📷', 'sort_order' => 4],
            ['name' => 'Đồ điện tử', 'slug' => 'do-dien-tu', 'icon' => '🔌', 'sort_order' => 5],
            ['name' => 'Nội thất', 'slug' => 'noi-that', 'icon' => '🛋️', 'sort_order' => 6],
            ['name' => 'Xe máy', 'slug' => 'xe-may', 'icon' => '🏍️', 'sort_order' => 7],
            ['name' => 'Ô tô', 'slug' => 'o-to', 'icon' => '🚗', 'sort_order' => 8],
            ['name' => 'Thời trang', 'slug' => 'thoi-trang', 'icon' => '👗', 'sort_order' => 9, 'children' => [
                ['name' => 'Quần áo nam', 'slug' => 'quan-ao-nam'],
                ['name' => 'Quần áo nữ', 'slug' => 'quan-ao-nu'],
                ['name' => 'Giày dép', 'slug' => 'giay-dep'],
            ]],
            ['name' => 'Đồng hồ', 'slug' => 'dong-ho', 'icon' => '⌚', 'sort_order' => 10],
            ['name' => 'Sách', 'slug' => 'sach', 'icon' => '📚', 'sort_order' => 11],
            ['name' => 'Đồ gia dụng', 'slug' => 'do-gia-dung', 'icon' => '🏠', 'sort_order' => 12],
            ['name' => 'Khác', 'slug' => 'khac', 'icon' => '📦', 'sort_order' => 13],
        ];

        foreach ($categories as $catData) {
            $children = $catData['children'] ?? [];
            unset($catData['children']);

            $parent = Category::create($catData);

            foreach ($children as $childData) {
                Category::create(array_merge($childData, [
                    'parent_id' => $parent->id,
                    'icon' => $catData['icon'],
                ]));
            }
        }

        // ── Sample Products ────────────────────────────────
        $this->call([
            LocationSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
