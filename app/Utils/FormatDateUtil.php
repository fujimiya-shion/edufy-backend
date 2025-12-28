<?php

namespace App\Utils;

class FormatDateUtil
{
    public static function formatDate($date)
    {
        if ($date) {
            $date = str_replace('-', '/', $date);
            return implode('-', array_reverse(explode('/', $date)));
        }
        return '';
    }
    public static function reformatDate($date)
    {
        if ($date) {
            return implode('/', array_reverse(explode('-', $date)));
        }
        return $date;
    }

}
