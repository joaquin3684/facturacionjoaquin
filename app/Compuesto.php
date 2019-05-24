<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 21/05/19
 * Time: 08:42
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compuesto extends Model
{
    use SoftDeletes;

    protected $table = 'compuestos';

    public function calcularStock(Producto $producto, $cant = 0)
    {
        return $this->componentes->map(function($c){
            return round($c->stock / $c->pivot->cantidad, 0, PHP_ROUND_HALF_DOWN);
        })->min();
    }

    public function aumentarStock($cantidad)
    {
        $this->componentes->each(function($c) use ($cantidad){
            $c->aumentarStock($cantidad * $c->pivot->cantidad);
        });
    }

    public function descontarStock($cantidad)
    {
        $this->componentes->each(function($c) use ($cantidad){
            $c->descontarStock($cantidad * $c->pivot->cantidad);
        });
    }

    public function producto()
    {
        return $this->morphOne('App\Producto', 'tipo', 'tipo_type','tipo_id');
    }

    public function componentes()
    {
        return $this->belongsToMany('App\Producto', 'composicion', 'id_producto', 'id_compuesto')->withPivot('cantidad');
    }




}