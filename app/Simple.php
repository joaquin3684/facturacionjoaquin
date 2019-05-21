<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 21/05/19
 * Time: 08:41
 */

namespace App;


class Simple extends Producto
{

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->doesntHave('compuestos');
        });
    }
}