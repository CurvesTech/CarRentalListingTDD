<?php

namespace App\Enums;

enum CarShape : string {
    case SEDAN = 'sedan';
    case HATCHBACK = 'hatchback';
    case SUV = 'suv';
    case OTHER = 'other';

    public static function values() : array {
        return array_map(function(self $case) {
            return $case->value;
        }, static::cases());
    }
}