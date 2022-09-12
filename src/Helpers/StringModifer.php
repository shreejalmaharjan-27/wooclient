<?php

namespace Shreejalmaharjan27\Wooclient\Helpers;

class StringModifer {

    public static function truncate(string $string, int $length = 50, string $append = '...')
    {
        $string = trim($string);

        if (strlen($string) > $length) {
            $string = wordwrap($string, $length);
            $string = explode("\n", $string, 2);
            $string = $string[0] . $append;
        }

        return $string;
    }
}