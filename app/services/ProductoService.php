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

    public function store($nombre, $descripcion, $importe, $ptoReposicion, $idMl, $idEmpresa)
    {
        return Producto::create([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'importe' => $importe,
            'pto_reposicion' => $ptoReposicion,
            'id_ml' => $idMl,
            'id_empresa' => $idEmpresa
        ]);
    }

    public function update($nombre, $descripcion, $importe, $ptoReposicion, $idMl, $idEmpresa, $id)
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
        return $producto;
    }

    public function find($id)
    {
        return Producto::find($id);
    }

    public function delete($id)
    {
        Producto::destroy($id);
    }

    public function all($idEmpresa)
    {
        return Producto::where('id_empresa', $idEmpresa)->get();
    }

}