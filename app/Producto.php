<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    protected $table = 'productos';
    protected $fillable = ['nombre', 'descripcion', 'pto_reposicion', 'id_ml', 'importe', 'id_empresa', 'stock', 'tipo_type', 'tipo_id'];

    public function tipo()
    {
        return $this->morphTo();
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
            $rel->componentes()->attach($attributes['componentes']);
        } else
        {
            $rel = Simple::create([]);
        }
        $prod = parent::create($attributes);
        $prod->tipo()->save($rel);
        return $prod;
    }

    public function fill(array $attributes)
    {
        if($this->tipo instanceof Compuesto && (is_null($attributes['componentes']) || $attributes['componentes'] == []))
        {
            $rel = Simple::create([]);
            $this->tipo->componentes()->detach();
            $this->tipo()->dissociate();
            $this->tipo()->associate($rel);
        }
        else if($this->tipo instanceof Simple && (!is_null($attributes['componentes']) || $attributes['componentes'] != []))
        {
            $rel = Compuesto::create([]);
            $this->tipo()->dissociate();
            $this->tipo()->associate($rel);
            $rel->componentes()->attach($attributes['componentes']);
        } else if($this->tipo instanceof Compuesto)
        {
            $this->tipo->componentes->sync($attributes['componentes']);
        }
        return parent::fill($attributes);
    }

    public function delete()
    {
        $this->tipo->delete();
        return parent::delete();
    }

}
