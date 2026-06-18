<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ thanh toán',
            self::SUCCESS => 'Thành công',
            self::FAILED => 'Thất bại',
            self::REFUNDED => 'Đã hoàn tiền',
        };
    }
}
