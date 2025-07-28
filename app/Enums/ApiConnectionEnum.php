<?php

namespace App\Enums;

enum ApiConnectionEnum: string
{
    case ONEDG = '1DG API';

    public function getLabel(): string
    {
        return match ($this) {
            self::ONEDG => 'OneDG',
        };
    }
}
