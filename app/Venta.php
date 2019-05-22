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
        $fac = parent::create($attributes);
        $items = $this->createItems($attributes['items']);
        $items->each(function($i){ $this->descontarStock($i->producto, $i->cantidad);});
        return $fac;
    }

    public function facturaElectronica()
    {
        //TODO hacer la magia de la afip
    }

    public function createItems(array $attributes = [])
    {
        foreach ($attributes as $a)
            $a['id_factura'] = $this->id;
        $this->items()->createMany($attributes);
    }

    public function descontarStock(Producto $producto, $cantidad)
    {
        $producto->descontarStock($cantidad);

    }
}