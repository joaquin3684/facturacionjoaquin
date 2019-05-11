<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 10/05/19
 * Time: 12:50
 */

namespace App\services;


use App\ItemFactura;

class ItemFacturaService
{
    public function store($cantidad, $impuesto, $importe, $idFactura, $idProducto)
    {
        return ItemFactura::create([
            'cantidad' => $cantidad,
            'impuesto' => $impuesto,
            'importe' => $importe,
            'id_factura' => $idFactura,
            'id_producto' => $idProducto,
        ]);
    }
}