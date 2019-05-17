<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 17/05/19
 * Time: 18:31
 */

namespace App\services\ML;


use App\services\ML\Dom\OrdenML;

class OrdenMapper
{
    public static function map($ordenes)
    {
        if(is_array($ordenes))
            return collect($ordenes)->map(function($o){
                return new OrdenML();

            });
    }
}