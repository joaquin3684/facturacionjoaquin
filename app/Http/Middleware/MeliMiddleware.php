<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 16/05/19
 * Time: 19:55
 */

namespace App\Http\Middleware;


use App\services\ML\Meli;
use App\User;
use Closure;

class MeliMiddleware
{
    public function handle($request, Closure $next)
    {

        $user = User::find($request['userId']);
        $meli = new Meli($user);

        $request->request->add(['meli' => $meli]);

        return $next($request);

    }
}