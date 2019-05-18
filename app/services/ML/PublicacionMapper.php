<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 17/05/19
 * Time: 12:10
 */

namespace App\services\ML;


use App\services\ML\Dom\PublicacionML;
use Illuminate\Database\Eloquent\Collection;

class PublicacionMapper
{

    /**{
    "id": "MLA600190449",
    "site_id": "MLA",
    "title": "Iphone 6 64gb Space Gray Liberado",
    "subtitle": null,
    "seller_id": 118617944,
    "category_id": "MLA352543",
    "official_store_id": null,
    "price": 16550,
    "base_price": 16550,
    "original_price": null,
    "currency_id": "ARS",
    "initial_quantity": 2,
    "available_quantity": 2,
    "sold_quantity": 0,
    "buying_mode": "buy_it_now",
    "listing_type_id": "bronze",
    "start_time": "2016-01-13T18:10:29.000Z",
    "stop_time": "2016-03-13T18:10:29.000Z",
    "condition": "new",
    "permalink": "http://articulo.mercadolibre.com.ar/MLA-600190449-iphone-6-64gb-space-gray-liberado-_JM",
    "thumbnail": "http://mla-s1-p.mlstatic.com/873411-MLA20547233702_012016-I.jpg",
    "secure_thumbnail": "https://a248.e.akamai.net/mla-s1-p.mlstatic.com/873411-MLA20547233702_012016-I.jpg",
    "pictures": [
        {
        "id": "873411-MLA20547233702_012016",
        "url": "http://mla-s1-p.mlstatic.com/873411-MLA20547233702_012016-O.jpg",
        "secure_url": "https://a248.e.akamai.net/mla-s1-p.mlstatic.com/873411-MLA20547233702_012016-O.jpg",
        "size": "225x225",
        "max_size": "225x225",
        "quality": ""
        },
        {
        "id": "234411-MLA20547233720_012016",
        "url": "http://mla-s1-p.mlstatic.com/234411-MLA20547233720_012016-O.jpg",
        "secure_url": "https://a248.e.akamai.net/mla-s1-p.mlstatic.com/234411-MLA20547233720_012016-O.jpg",
        "size": "259x194",
        "max_size": "259x194",
        "quality": ""
        },
        {
        "id": "768311-MLA20547233735_012016",
        "url": "http://mla-s1-p.mlstatic.com/768311-MLA20547233735_012016-O.jpg",
        "secure_url": "https://a248.e.akamai.net/mla-s1-p.mlstatic.com/768311-MLA20547233735_012016-O.jpg",
        "size": "300x168",
        "max_size": "300x168",
        "quality": ""
        }
    ],
    "video_id": null,
    "descriptions": [
        {
        "id": "MLA600190449-1007729488"
        }
    ],
    "accepts_mercadopago": true,
    "non_mercado_pago_payment_methods": [
    ],
    "shipping": {
        "mode": "me2",
        "local_pick_up": true,
        "free_shipping": true,
        "free_methods": [
            {
            "id": 73328,
            "rule": {
                "free_mode": "country",
                "value": null
                }
            }
            ],
    "dimensions": null,
    "tags": [
    ]
    },
    "international_delivery_mode": "none",
    "seller_address": {
    "id": 138834162,
    "comment": "",
    "address_line": "",
    "zip_code": "",
    "city": {
    "id": "TUxBQ05FVXF1ZW4",
    "name": "Neuquén"
    },
    "state": {
    "id": "AR-Q",
    "name": "Neuquén"
    },
    "country": {
    "id": "AR",
    "name": "Argentina"
    },
    "latitude": -38.95628353,
    "longitude": -68.12749595,
    "search_location": {
    "neighborhood": {
    "id": "",
    "name": ""
    },
    "city": {
    "id": "TUxBQ05FVXF1ZW4",
    "name": "Neuquén"
    },
    "state": {
    "id": "TUxBUE5FVW4xMzMzNQ",
    "name": "Neuquén"
    }
    }
    },
    "seller_contact": null,
    "location": {
    },
    "geolocation": {
    "latitude": -38.96055205,
    "longitude": -68.12525497
    },
    "coverage_areas": [
    ],
    "attributes": [
    ],
    "listing_source": "",
    "variations": [
    ],
    "status": "active",
    "sub_status": [
    ],
    "tags": [
    ],
    "warranty": null,
    "catalog_product_id": null,
    "parent_item_id": null,
    "differential_pricing": null,
    "deal_ids": [
    ],
    "automatic_relist": false,
    "date_created": "2016-01-13T18:10:29.000Z",
    "last_updated": "2016-01-13T18:26:54.000Z"
    }*/

    public static function map($publi)
    {
        if($publi instanceof Collection)
            return $publi->map(function($p){
                return new PublicacionML($p->id, $p->site_id, $p->title, $p->subtitle, $p->seller_id, $p->price, $p->available_quantity);
            });
        else
            return new PublicacionML($publi->id, $publi->site_id, $publi->title, $publi->subtitle, $publi->seller_id, $publi->price, $publi->available_quantity);

    }
}