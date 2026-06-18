<?php

namespace App\Enums;

enum ReportStatus: string
{
    case PENDING = 'pending';
    case REVIEWED = 'reviewed';
    case RESOLVED = 'resolved';
    case DISMISSED = 'dismissed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ xử lý',
            self::REVIEWED => 'Đang xem xét',
            self::RESOLVED => 'Đã xử lý',
            self::DISMISSED => 'Bỏ qua',
        };
    }
}
