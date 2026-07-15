<?php

namespace App\Enums;

enum ProductSize: string
{
    case Small = 'small';
    case Medium = 'medium';
    case Big = 'big';

    public function label(): string
    {
        return match ($this) {
            self::Small => 'Small',
            self::Medium => 'Medium',
            self::Big => 'Big',
        };
    }
}
