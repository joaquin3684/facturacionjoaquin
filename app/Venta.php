<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 21/05/19
 * Time: 08:31
 */

namespace App;


use App\Enum\TipoFactura;
use App\services\ProductoFactory;

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
        $fac = parent::create($attributes);
        $items = $fac->createItems($attributes['items']);

        $items->map(function($i){return [$i->producto, $i->cantidad];})->each(function($p){$p[0]->descontarStock($p[1]);});
        return $fac;
    }

    public function facturaElectronica()
    {
        //TODO hacer la magia de la afip
    }

}