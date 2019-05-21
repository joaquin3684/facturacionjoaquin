<?php

namespace App\Http\Controllers;

use App\Compra;
use App\ItemFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function store(Request $request)
    {
        Db::transaction(function() use ($request){

            $total = array_sum(array_map(function($item){
                return $item['importe'];
            }, $request['items']));

            $impuestos = array_sum(array_map(function($item){
                return $item['impuesto'];
            }, $request['items']));

            $compra = Compra::create([
                'cuit_emisor' => $request['cuit_emisor'],
                'total_bruto' => $total,
                'total_impuestos' => $impuestos,
                'total_neto' => $total - $impuestos,
                'tipo' => $request['tipo'],
                'facturado' => $request['facturado'],
                'ml' => $request['ml'],
                'numero' => $request['nro'],
                'fecha' => $request['fecha'],
                'id_empresa' => $request['idEmpresa'],
            ]);
            foreach ($request['items'] as $i)
                ItemFactura::create([
                    'cantidad' => $i['cantidad'],
                    'impuesto' => $i['impuesto'],
                    'importe' => $i['importe'],
                    'id_factura' => $compra->id,
                    'id_producto' => $i['id_producto'],
                ]);

        });
    }

    public function update(Request $request, $id)
    {
        Db::transaction(function() use ($request, $id) {
            $total = array_sum(array_map(function($item){
                return $item['importe'];
            }, $request['items']));

            $impuestos = array_sum(array_map(function($item){
                return $item['impuesto'];
            }, $request['items']));

            $compra = Compra::find($id);
            $compra->fill([
                'cuit_emisor' => $request['cuit_emisor'],
                'total_bruto' => $total,
                'total_impuestos' => $impuestos,
                'total_neto' => $total - $impuestos,
                'tipo' => $request['tipo'],
                'facturado' => $request['facturado'],
                'ml' => $request['ml'],
                'numero' => $request['nro'],
                'fecha' => $request['fecha'],
                'id_empresa' => $request['idEmpresa'],
            ]);
            $compra->save();

            $items = ItemFactura::where('id_factura', $compra->id)->get();
            $items->each(function($i){$i->delete();});


            foreach ($request['items'] as $i)
                ItemFactura::create([
                    'cantidad' => $i['cantidad'],
                    'impuesto' => $i['impuesto'],
                    'importe' => $i['importe'],
                    'id_factura' => $compra->id,
                    'id_producto' => $i['id_producto'],
                ]);

        });
    }

    public function all(Request $request, $fechaDesde, $fechaHasta)
    {
        return Compra::with('items.producto')
            ->where('fecha', '<=', $fechaHasta)
            ->where('fecha', '>=', $fechaDesde)
            ->where('id_empresa', $request['idEmpresa'])
            ->get();    }

    public function find($id)
    {
        return Compra::with('items.producto')->find($id);
    }

    public function delete($id)
    {
        $compra = Compra::find($id);
        $items = ItemFactura::where('id_factura', $compra->id)->get();
        $items->each(function($i){$i->delete();});

        $compra->delete();
    }
}
