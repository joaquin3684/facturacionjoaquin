<?php
namespace App\Traits;
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 10/10/17
 * Time: 12:43
 */
trait Conversion
{
    public function toArray($object) {
        $arr =  collect(get_object_vars($object));
        return $arr->map(function ($elem) {
            if(is_a($elem, 'Illuminate\Support\Collection'))
            {
                return $elem->map(function ($el){
                    return $el->toArray($el);
                })->toArray();
            }
            else if(is_object($elem))
            {
                return $elem->toArray($elem);
            }
            else {
                return $elem;
            }
        })->toArray();
    }
}