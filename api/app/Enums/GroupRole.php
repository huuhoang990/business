<?php

namespace App\Enums;

enum GroupRole: int
{
    case ADMIN = 1;
    case EDITOR = 2;
    case REVIEWER = 3;

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Admin',
            self::EDITOR => 'Editor',
            self::REVIEWER => 'Reviewer',
        };
    }
}
