<?php

namespace App\Support;

use Illuminate\Support\Str;

class DeliveryPolicy
{
    public static function cities(): array
    {
        return config('delivery.free_cities', []);
    }

    public static function normalizeCity(?string $city): string
    {
        return Str::lower(Str::ascii(trim((string) $city)));
    }

    public static function supportsCity(?string $city): bool
    {
        $city = self::normalizeCity($city);

        return $city !== '' && collect(self::cities())
            ->contains(fn (string $supportedCity) => self::normalizeCity($supportedCity) === $city);
    }
}
