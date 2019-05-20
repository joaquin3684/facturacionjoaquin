<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 09/05/19
 * Time: 10:59
 */

namespace App\services;


use App\Producto;

class ProductoService
{

    public function store($nombre, $descripcion, $importe, $ptoReposicion, $idMl, $idEmpresa, $compuestos)
    {
        $prod = Producto::create([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'importe' => $importe,
            'pto_reposicion' => $ptoReposicion,
            'id_ml' => $idMl,
            'id_empresa' => $idEmpresa
        ]);

        $c = collect($compuestos)->mapWithKeys(function($c){return array($c['idProducto'] => array('cantidad' => $c['cantidad']));});
        $prod->compuestos()->attach($c->toArray());
        return $prod;
    }

    public function aumentarStock(Producto $prod, $cantidad)
    {
        if($prod->compuestos->count() > 0)
        {
        }

    }

    public function reducirStock(Producto $prod, $cantidad)
    {

    }

    public function update($nombre, $descripcion, $importe, $ptoReposicion, $idMl, $idEmpresa, $compuestos, $id)
    {
        $producto = $this->find($id);
        $producto->fill([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'importe' => $importe,
            'pto_reposicion' => $ptoReposicion,
            'id_ml' => $idMl,
            'id_empresa' => $idEmpresa

        ]);
        $producto->save();
        $c = collect($compuestos)->mapWithKeys(function($c){return array($c['idProducto'] => array('cantidad' => $c['cantidad']));});
        $producto->compuestos()->sync($c->toArray());
        return $producto;
    }

    public function find($id)
    {
        $prod = Producto::with('compuestos')->find($id);
        if($prod->compuestos->count() > 0)
        {
            $prod->stock = $this->calcularStock($prod);
            return $prod;
        } else
        {
            return $prod;
        }
    }

    public function calcularStock(Producto $prod)
    {
        return $prod->compuestos->map(function($c){
            return round($c->stock / $c->pivot->cant, 0, PHP_ROUND_HALF_DOWN);
        })->min();
    }

    public function delete($id)
    {
        Producto::destroy($id);
    }

    public function all($idEmpresa)
    {
        $prods = Producto::with('compuestos')->where('id_empresa', $idEmpresa)->get();
        $prods->each(function($p){
            if($p->compuestos->count() > 0)
            {
                $p->stock = $this->calcularStock($p);

            } else
            {
                return $p;
            }
        });
        return $prods;
    }

}