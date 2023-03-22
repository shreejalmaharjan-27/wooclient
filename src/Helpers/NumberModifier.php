<?php

namespace Shreejalmaharjan27\Wooclient\Helpers;

class NumberModifier {
    public static function floatZeroIfZero(float $num)
    {
        if (is_null($num)) return $num;

        if (!$num) return 0.00;

        return $num;
    }
}