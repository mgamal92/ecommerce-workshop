<?php

namespace App\Traits;

trait Helpers
{
    public function num_format(float $number, $decimals = 2, $dec_separator = ".", $thousands_separator = null)
    {
        return number_format($number, $decimals, $dec_separator, $thousands_separator);
    }
}
