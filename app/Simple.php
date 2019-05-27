<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 21/05/19
 * Time: 08:41
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Simple extends Model
{
    use SoftDeletes;

    protected  $table = 'simples';
    public function producto()
    {
        return $this->morphOne('App\Producto', 'tipo', 'tipo_type','tipo_id');
    }

    public function calcularStock(Producto $prod, $cant = 0)
    {
        return $cant;
    }

    public function aumentarStock($cantidad)
    {
        $this->producto = $this->producto->fresh();
        $this->producto->stock += $cantidad;
        $this->producto->save();
    }

    public function descontarStock($cantidad)
    {
        $this->producto = $this->producto->fresh();
        $this->producto->stock -= $cantidad;
        $this->producto->save();
    }

    public function componentes()
    {
        return $this->belongsTo($this->newQuery(), $this, '', '');
    }

}