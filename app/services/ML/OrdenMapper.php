<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 17/05/19
 * Time: 18:31
 */

namespace App\services\ML;


use App\services\ML\Dom\OrdenML;

class OrdenMapper
{
    /*"id": 768570754,
   "status": "paid",
   "status_detail": null,
   "date_created": "2013-05-27T10:01:50.000-04:00",
   "date_closed": "2013-05-27T10:04:07.000-04:00",
   "order_items": - [
       - {
   "item": - {
   "id": "MLB12345678",
   "title": "Samsung Galaxy",
   "variation_id": null,
   "variation_attributes": [
   ],
   },
   "quantity": 1,
         "unit_price": 499,
         "currency_id": "BRL",
       },
     ],
     "total_amount": 499,
     "currency_id": "BRL",
     "buyer": - {
       "id": "123456789",
       "nickname": "COMPRADORTESTE",
       "email": "b@b.com",
       "phone": - {
           "area_code": "11",
         "number": "12345678",
         "extension": null,
       },
       "first_name": "Comprador de testes",
       "last_name": "da Silva",
       "billing_info": - {
           "doc_type": "CPF",
         "doc_number": "12345678910",
       },
     },
     "seller": - {
       "id": "123456789",
       "nickname": "VENDEDORTESTES",
       "email": "a@a.com",
       "phone": - {
           "area_code": null,
         "number": "11 12345678",
         "extension": "11",
       },
       "first_name": "Vendedor de Testes",
       "last_name": "testes de documentacao",
     },
     "payments": - [
       - {
       "id": "596707837",
         "transaction_amount": 499,
         "currency_id": "BRL",
         "status": "approved",
         "date_created": null,
         "date_last_modified": null,
       },
     ],
     "feedback": - {
       "purchase": null,
       "sale": null,
     },
     "shipping": - {
       "id": 20676482441,
       "shipment_type": "shipping",
       "status": "handling",
       "date_created": "2013-05-27T10:03:28.000-04:00",
       "receiver_address": - {
           "id": 12345678,
         "address_line": "Rua dos testes 123  ",
         "zip_code": "01001000",
         "city": - {
               "id": "BR-SP-44",
           "name": "São Paulo",
         },
         "state": - {
               "id": "BR-SP",
           "name": "São Paulo",
         },
         "country": - {
               "id": "BR",
           "name": "Brasil",
         },
         "latitude": null,
         "longitude": null,
         "comment": null,
       },
       "currency_id": "BRL",
       "cost": 0,
     },
     "tags": - [
       "paid",
       "not_delivered",
   ],
   }*/
    public static function map($ordenes)
    {

        if(is_array($ordenes))
            return collect($ordenes)->map(function($o){
                return new OrdenML($o->id, $o->status, $o->fecha_creado, collect($o->order_items)->map(function($i){return $i->id;}), $o->total_amount, UserMapper::map($o->buyer), $o->shipping->status);
            });
        else
            return new OrdenML($ordenes->id, $ordenes->status, $ordenes->fecha_creado, collect($ordenes->order_items)->map(function($i){return $i->id;}), $ordenes->total_amount, UserMapper::map($ordenes->buyer), $ordenes->shipping->status);

    }
}