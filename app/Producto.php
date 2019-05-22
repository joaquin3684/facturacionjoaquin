<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    protected $table = 'productos';
    protected $fillable = ['nombre', 'descripcion', 'pto_reposicion', 'id_ml', 'importe', 'id_empresa', 'stock'];

    public function compuestos()
    {
        return $this->belongsToMany('App\Producto', 'composicion', 'id_producto', 'id_compuesto')->withPivot('cantidad');
    }


}
