<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 17/05/19
 * Time: 12:10
 */

namespace App\services\ML;


use App\services\ML\Dom\UserML;

class UserMapper
{

        /**{
        "id": 202593498,
        "nickname": "TETE2870021",
        "registration_date": "2016-01-06T11:31:42.000-04:00",
        "country_id": "AR",
        "address": {
        "state": "AR-C",
        "city": "Palermo"
        },
        "user_type": "normal",
          "tags": [
            "normal",
            "test_user",
            "user_info_verified"
        ],
          "logo": null,
          "points": 100,
          "site_id": "MLA",
          "permalink": "http://perfil.mercadolibre.com.ar/TETE2870021",
          "seller_reputation": {
            "level_id": null,
            "power_seller_status": null,
            "transactions": {
                "period": "historic",
            "total": 0,
            "completed": 0,
            "canceled": 0,
            "ratings": {
                    "positive": 0,
                "negative": 0,
                "neutral": 0
            }
            }
          },
          "buyer_reputation": {
            "tags": [
            ]
          },
          "status": {
            "site_status": "active"
          }
        }*/

    public static function map($user)
    {
        return new UserML($user->nickname, $user->first_name, $user->last_name, $user->email, $user->id);
    }
}