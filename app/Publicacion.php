<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    protected $table = 'publicaciones';

    protected $fillable = [
        'id_ml',
        'titulo',
        'subtitulo',
        'precio',
        'precio_base',
        'precio_original',
        'moneda',
        'stock_disponible',
        'cantidad_vendida',
        'tipo_lista',
        'fecha_cierre',
        'fecha_inicio',
        'link_ml',
        'foto',
        'envio',
        'estado',
        'sku',
        'id_producto',
        'id_empresa'
    ];

    public function linkear($idProducto)
    {
        $this->id_producto = $idProducto;
        $this->save();
    }

    public function deslinkear()
    {
        $this->id_producto = null;
        $this->save();
    }
}
