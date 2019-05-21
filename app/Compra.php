<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 21/05/19
 * Time: 08:31
 */

namespace App;


use App\Enum\TipoFactura;

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
        return parent::create($attributes);
    }


}