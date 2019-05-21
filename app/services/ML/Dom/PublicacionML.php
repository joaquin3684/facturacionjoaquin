<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 17/05/19
 * Time: 12:20
 */

namespace App\services\ML\Dom;


use App\Traits\Conversion;

class PublicacionML
{
    use Conversion;

    public $id;
    public $site_id;
    public $title;
    public $subtitle;
    public $seller_id;
    public $precio;
    public $stock;

    /**
     * PublicacionML constructor.
     * @param $id
     * @param $site_id
     * @param $title
     * @param $subtitle
     * @param $seller_id
     * @param $precio
     * @param $stock
     */
    public function __construct($id, $site_id, $title, $subtitle, $seller_id, $precio, $stock)
    {
        $this->id = $id;
        $this->site_id = $site_id;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->seller_id = $seller_id;
        $this->precio = $precio;
        $this->stock = $stock;
    }


}