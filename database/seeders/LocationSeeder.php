<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'Hà Nội' => ['HN', ['Ba Đình', 'Hoàn Kiếm', 'Hai Bà Trưng', 'Đống Đa', 'Tây Hồ', 'Cầu Giấy', 'Thanh Xuân', 'Hoàng Mai', 'Long Biên']],
            'Hồ Chí Minh' => ['SG', ['Quận 1', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6', 'Quận 7', 'Quận 8', 'Quận 10', 'Quận 11', 'Quận 12', 'Tân Bình', 'Bình Thạnh', 'Phú Nhuận', 'Gò Vấp']],
            'Đà Nẵng' => ['DN', ['Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Ngũ Hành Sơn', 'Liên Chiểu', 'Cẩm Lệ']],
            'Hải Phòng' => ['HP', ['Hồng Bàng', 'Ngô Quyền', 'Lê Chân', 'Hải An', 'Kiến An', 'Đồ Sơn']],
            'Cần Thơ' => ['CT', ['Ninh Kiều', 'Bình Thủy', 'Cái Răng', 'Ô Môn', 'Thốt Nốt']],
        ];

        foreach ($locations as $provinceName => $data) {
            $provinceCode = $data[0];
            $districts = $data[1];

            $provinceId = DB::table('provinces')->insertGetId([
                'name' => $provinceName,
                'code' => $provinceCode,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($districts as $districtName) {
                DB::table('districts')->insert([
                    'province_id' => $provinceId,
                    'name' => $districtName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
