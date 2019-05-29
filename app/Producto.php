<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    protected $table = 'productos';
    protected $with = ['tipo'];
    protected $fillable = ['nombre', 'descripcion', 'pto_reposicion', 'importe', 'id_empresa', 'stock', 'tipo_type', 'tipo_id', 'impuesto'];

    public function tipo()
    {
        return $this->morphTo();
    }

    public function empresa()
    {
        return $this->belongsTo('App\Empresa', 'id_empresa', 'id');
    }

    public function getStockAttribute($value)
    {
        return $this->tipo->calcularStock($this, $value);
    }

    public function aumentarStock($cantidad)
    {
        $this->tipo->aumentarStock($cantidad);
    }

    public function descontarStock($cantidad)
    {
        $this->tipo->descontarStock($cantidad);
    }

    public function create(array $attributes = [])
    {
        if(!is_null($attributes['componentes']) || $attributes['componentes'] != [])
        {
            $rel = Compuesto::create([]);
            $attributes['tipo_type'] = 'App\Compuesto';
            $componentes = collect($attributes['componentes']);
            $componentes = $componentes->mapWithKeys(function($c){
                return [$c['idProducto'] => ['cantidad' => $c['cantidad']]];
            });
            $rel->componentes()->attach($componentes);
        } else
        {
            $rel = Simple::create([]);
            $attributes['tipo_type'] = 'App\Simple';

        }
        $attributes['tipo_id'] = $rel->id;

        $prod = parent::create($attributes);
        $prod->tipo()->save($rel);
        return $prod;
    }

    public function fill(array $attributes)
    {
        if($this->tipo instanceof Compuesto && (is_null($attributes['componentes']) || $attributes['componentes'] == []))
        {
            $rel = Simple::create([]);
            $this->tipo->delete();
            $this->tipo()->dissociate();
            $this->tipo()->associate($rel);
        }
        else if($this->tipo instanceof Simple && (!is_null($attributes['componentes']) || $attributes['componentes'] != []))
        {
            $rel = Compuesto::create([]);
            $this->tipo->delete();
            $this->tipo()->dissociate();
            $this->tipo()->associate($rel);
            $componentes = collect($attributes['componentes']);
            $componentes = $componentes->mapWithKeys(function($c){
                return [$c['idProducto'] => ['cantidad' => $c['cantidad']]];
            });
            $rel->componentes()->attach($componentes);
        } else if($this->tipo instanceof Compuesto)
        {
            $componentes = collect($attributes['componentes']);
            $componentes = $componentes->mapWithKeys(function($c){
                return [$c['idProducto'] => ['cantidad' => $c['cantidad']]];
            });
            $this->tipo->componentes->sync($componentes);
        }
        return parent::fill($attributes);
    }

    public function delete()
    {
        $this->tipo->delete();
        return parent::delete();
    }

    public function mapToFront()
    {
        $a = $this->toArray();
        return $this->prodToArray($a);
    }

    public function prodToArray(&$arr)
    {

        if(isset($arr['tipo']['componentes']))
        {
            $comp = [];
            foreach ($arr['tipo']['componentes'] as $componente)
                array_push($comp, $this->prodToArray($componente));

            if(isset($arr['pivot'])) {
                $arr['cantidad'] = $arr['pivot']['cantidad'];
                unset($arr['tipo'], $arr['pivot']);
            } else {
                unset($arr['tipo']);
            }
            $arr['componentes'] = $comp;
            return $arr;
        } else {
            if(isset($arr['pivot'])) {
                $arr['cantidad'] = $arr['pivot']['cantidad'];
                unset($arr['tipo'], $arr['pivot']);
            } else {
                unset($arr['tipo']);
            }
            return $arr;
        }

    }

    public function componentesToArray(&$componentes)
    {

        if(isset($componentes['tipo']['componentes']))
        {

            $a = $componentes['tipo']['componentes'];
            unset($componentes['tipo']);
            $componentes['componentes'] = $this->componentesToArray($componentes['tipo']['componentes']);
        } else
            return $componentes;


    }
}
