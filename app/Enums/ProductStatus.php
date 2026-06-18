<?php

namespace App\Enums;

enum ProductStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case SOLD = 'sold';
    case HIDDEN = 'hidden';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ duyệt',
            self::ACTIVE => 'Đang bán',
            self::SOLD => 'Đã bán',
            self::HIDDEN => 'Tạm ẩn',
            self::REJECTED => 'Từ chối',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::ACTIVE => 'green',
            self::SOLD => 'blue',
            self::HIDDEN => 'gray',
            self::REJECTED => 'red',
        };
    }
}
