<?php

namespace App\Helpers;

class PriceFormatter
{
    /**
     * Format a price to currency (e.g., Rp1.000.000).
     *
     * @param int|float $price
     * @return string
     */
    public static function format($price)
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }
}
