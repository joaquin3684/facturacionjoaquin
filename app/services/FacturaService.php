<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 09/05/19
 * Time: 11:00
 */

namespace App\services;


use App\Factura;
use App\ItemFactura;

class FacturaService
{
    private $itemService;

    public function __construct()
    {
        $this->itemService = new ItemFacturaService();
    }

    public function store($cuitEmisor, $cuitReceptor, $tipo, $facturado, $ml, $items, $nro, $fecha, $idEmpresa)
    {
        $total = array_sum(array_map(function($item){
            return $item['importe'];
        }, $items));

        $impuestos = array_sum(array_map(function($item){
            return $item['impuesto'];
        }, $items));

        $factura = Factura::create([
            'cuit_emisor' => $cuitEmisor,
            'cuit_receptor' => $cuitReceptor,
            'total_bruto' => $total,
            'total_impuestos' => $impuestos,
            'total_neto' => $total - $impuestos,
            'tipo' => $tipo,
            'facturado' => $facturado,
            'ml' => $ml,
            'numero' => $nro,
            'fecha' => $fecha,
            'id_empresa' => $idEmpresa
        ]);

        foreach ($items as $item)
            $this->itemService->store($item['cantidad'], $item['impuesto'], $item['importe'], $factura->id, $item['id_producto']);
    }

    public function all($fechaDesde, $fechaHasta, $idEmpresa)
    {
        return Factura::with('items')
            ->where('fecha', '<=', $fechaHasta)
            ->where('fecha', '>=', $fechaDesde)
            ->where('id_empresa', $idEmpresa)
            ->get();
    }

    public function find($id)
    {
        return Factura::with('items')->find($id);
    }

    public function delete($id)
    {
        $factura = $this->find($id);
        foreach($factura->items as $item)
            $this->itemService->delete($item->id);

        $factura->delete();
    }

    public function update($cuitEmisor, $cuitReceptor, $tipo, $facturado, $ml, $items, $nro, $fecha, $idEmpresa, $id)
    {
        $this->delete($id);
        $this->store($cuitEmisor, $cuitReceptor, $tipo, $facturado, $ml, $items, $nro, $fecha, $idEmpresa);
    }

}