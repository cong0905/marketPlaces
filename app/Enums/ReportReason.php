<?php

namespace App\Enums;

enum ReportReason: string
{
    case FRAUD = 'fraud';
    case FAKE = 'fake';
    case SPAM = 'spam';
    case INAPPROPRIATE = 'inappropriate';

    public function label(): string
    {
        return match ($this) {
            self::FRAUD => 'Lừa đảo',
            self::FAKE => 'Hàng giả',
            self::SPAM => 'Spam',
            self::INAPPROPRIATE => 'Nội dung không phù hợp',
        };
    }
}
