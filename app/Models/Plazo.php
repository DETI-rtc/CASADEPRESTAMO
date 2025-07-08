<?php

namespace App\Models;
use App\Models\Plazo;
use App\Models\Credito;
use Illuminate\Database\Eloquent\Model;

class Plazo extends Model
{
    protected $table='plazo_max';
   
     protected $fillable = [
    'idmodalidad','identidad','mont_min','mont_max','pla_min','pla_max','estado'
    ];
    public function entidad()
    {
        return $this->belongsto(Delista::class,'identidad');
    }
    public function modalidad()
    {
        return $this->belongsto(Delista::class,'idmodalidad');
    }
    public function creditos()
    {
        return $this->hasMany(Credito::class,'idplazo');
    }
}
