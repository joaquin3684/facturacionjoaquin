<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 11/05/19
 * Time: 21:24
 */

namespace App\services\ML;



class AuthMLService
{
    public function auth()
    {
        $meli = new Meli('2382179841161472', 'X6U4B4gZKELFsY739dANIwJ1qFuD5Bo4');
        $redirectUrl = $meli->getAuthUrl("https://facturacionjoaquin.herokuapp.com/autenticar",Meli::$AUTH_URL['MLA']); //  Don't forget to change the $AUTH_URL value to match your user's Site Id.
        return $redirectUrl;
    }

    public function autenticar($codigo)
    {
        $meli = new Meli('2382179841161472', 'X6U4B4gZKELFsY739dANIwJ1qFuD5Bo4');

        $user = $meli->authorize($codigo, "https://facturacionjoaquin.herokuapp.com");
        // Now we create the sessions with the authenticated user
        //$access_token = $user['body']->access_token;
        //$_SESSION['expires_in'] = time() + $user['body']->expires_in;
        //$_SESSION['refresh_token'] = $user['body']->refresh_token;
        //$params = array('access_token' => $access_token);
        //return $meli->get('/users/me', $params);
        return $user;
    }
}