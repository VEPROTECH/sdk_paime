<?php
/**
 * Created by PhpStorm.
 * User: Verbeck DEGBESSE
 * Date: 23/09/2018
 * Time: 05:24
 */

class Converter
{

    public static function formatToNumber($value, $decimals = 2)
    {
        if (trim($value) != null) {
            return number_format($value, null, '.', '  ');
        }
        return null;
    }


}