<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case COD = 'cod';
    case VNPAY = 'vnpay';
    case MOMO = 'momo';
    case ZALOPAY = 'zalopay';

    public function label(): string
    {
        return match ($this) {
            self::COD => 'Thanh toán khi nhận hàng',
            self::VNPAY => 'VNPay',
            self::MOMO => 'MoMo',
            self::ZALOPAY => 'ZaloPay',
        };
    }
}
