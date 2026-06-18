<?php

namespace App\Enums;

enum UserRole: string
{
    case USER = 'user';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::USER => 'Người dùng',
            self::ADMIN => 'Quản trị viên',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }
}
