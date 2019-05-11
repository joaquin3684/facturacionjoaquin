<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';
    protected $fillable = ['cuit_emisor' , 'cuit_receptor', 'total_bruto' , 'total_impuestos', 'total_neto', 'tipo', 'facturado', 'ml', 'numero' , 'fecha', 'id_empresa'];

    public function items()
    {
        return $this->hasMany('App\ItemFactura','id_factura', 'id');
    }
}
