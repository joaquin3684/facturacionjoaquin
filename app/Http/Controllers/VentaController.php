<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function store(Request $request)
    {
        Db::transaction(function() use ($request){

            $items = collect($request['items']);
            $prods = Producto::findMany($items->map(function($i){return $i['idProducto'];}));

            $total = $prods->sum(function($p){return $p->importe;});
            $impuestos = round($prods->sum(function($p){return $p->importe * $p->impuesto / 100;}), 2);

            (new Venta())->create([
                'cuit_receptor' => $request['cuit_receptor'],
                'total_bruto' => $total,
                'total_impuestos' => $impuestos,
                'total_neto' => $total - $impuestos,
                'tipo' => $request['tipo'],
                'facturado' => $request['facturado'],
                'ml' => false,
                'fecha' => $request['fecha'],
                'id_empresa' => $request['idEmpresa'],
            ]);

        });
    }


    public function all(Request $request, $fechaDesde, $fechaHasta)
    {
        return Venta::with('items.producto')
            ->where('fecha', '<=', $fechaHasta)
            ->where('fecha', '>=', $fechaDesde)
            ->where('id_empresa', $request['idEmpresa'])
            ->get();
    }

    public function find($id)
    {
        return Venta::with('items.producto')->find($id);
    }

}
