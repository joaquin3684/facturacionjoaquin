<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemFactura extends Model
{
    protected $table = 'items_factura';
    protected $fillable = ['cantidad', 'impuesto', 'importe', 'id_factura', 'id_producto'];

    public function producto()
    {
        return $this->belongsTo('App\Producto', 'id_producto', 'id');
    }
}
