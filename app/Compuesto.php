<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 21/05/19
 * Time: 08:42
 */

namespace App;


class Compuesto extends Producto
{
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->has('compuestos');
        });
    }


    public function getStockAttribute($value)
    {
        return $this->compuestos->map(function($c){
            return round($c->stock / $c->pivot->cantidad, 0, PHP_ROUND_HALF_DOWN);
        })->min();

    }

    public function setStockAttribute($stock)
    {
        $this->compuestos->each(function($c) use ($stock){
            $c->stock = $c->pivot->cantidad * $stock;
            $c->save();
        });
    }

    public function descontarStock($cantidad)
    {
        $this->compuestos->each(function($c) use ($cantidad){
            $c->stock -= $c->pivot->cantidad * $cantidad;
            $c->save();
        });
    }

    public function aumentarStock($cantidad)
    {
        $this->compuestos->each(function($c) use ($cantidad){
            $c->stock += $c->pivot->cantidad * $cantidad;
            $c->save();
        });
    }
}