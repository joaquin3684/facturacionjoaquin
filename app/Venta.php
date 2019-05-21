<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 21/05/19
 * Time: 08:31
 */

namespace App;


use App\Enum\TipoFactura;

class Venta extends Factura
{
    protected $table = 'facturas';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {

            $query->where('tipo_fact', TipoFactura::VENTA);
        });
    }

    public function create(array $attributes = [])
    {
        if($attributes['facturado'])
        {
            $this->facturaElectronica();
        }
        $attributes['cuit_emisor'] = Empresa::find($attributes['id_empresa'])->cuit;
        $attributes['tipo_fact'] = TipoFactura::VENTA;
        return parent::create($attributes);
    }

    public function facturaElectronica()
    {

    }
}