<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 17/05/19
 * Time: 18:33
 */

namespace App\services\ML\Dom;


class OrdenML
{

    public $id;
    public $estado;
    public $fecha_creado;
    public $items;
    public $precio;
    public $comprador;
    public $estado_envio;

    /**
     * OrdenML constructor.
     * @param $id
     * @param $estado
     * @param $fecha_creado
     * @param $items
     * @param $precio
     * @param $comprador
     * @param $estado_envio
     */
    public function __construct($id, $estado, $fecha_creado, $items, $precio, $comprador, $estado_envio)
    {
        $this->id = $id;
        $this->estado = $estado;
        $this->fecha_creado = $fecha_creado;
        $this->items = $items;
        $this->precio = $precio;
        $this->comprador = $comprador;
        $this->estado_envio = $estado_envio;
    }




}