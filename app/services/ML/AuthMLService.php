<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 11/05/19
 * Time: 21:24
 */

namespace App\services\ML;



use Carbon\Carbon;

class AuthMLService
{
    private $meli;
    public function __construct(Meli $meli)
    {
        $this->meli = $meli;
    }

    public function auth()
    {

        $redirectUrl = $this->meli->getAuthUrl("https://facturacionjoaquin.herokuapp.com/autenticar",Meli::$AUTH_URL['MLA']); //  Don't forget to change the $AUTH_URL value to match your user's Site Id.
        return $redirectUrl;
    }

    public function autenticar($codigo)
    {

        $user = $this->meli->authorize($codigo, "https://facturacionjoaquin.herokuapp.com/autenticar");

        return array('token' => $user['body']->access_token, 'refresh_token' => $user['body']->refresh_token, 'expires' => Carbon::today()->addSeconds($user['body']->expires_in)->toDateTimeString());


    }
}