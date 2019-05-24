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

class Compra extends Factura
{
    protected $table = 'facturas';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->where('tipo_fact', TipoFactura::COMPRA);
        });
    }

    public function create(array $attributes = [])
    {
        $attributes['cuit_receptor'] = Empresa::find($attributes['id_empresa'])->cuit;
        $attributes['tipo_fact'] = TipoFactura::COMPRA;
        $attributes['entregado'] = false;
        $fac = parent::create($attributes);
        $items = $fac->createItems($attributes['items']);

        $items->map(function($i){return [$i->producto, $i->cantidad];})->each(function($p){$p[0]->aumentarStock($p[1]);});
        return $fac;
    }

    public function fill(array $attributes)
    {
        parent::fill($attributes);
        $this->deleteItems();
        $this->createItems($attributes['items']);

    }

    public function delete()
    {
        $this->deleteItems();
        return parent::delete();
    }


}