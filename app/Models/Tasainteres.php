<?php

namespace App\Models;
use App\Models\Delista;
use Illuminate\Database\Eloquent\Model;

class Tasainteres extends Model
{
    protected $table='tasa_int';
   
     protected $fillable = [
    'idmodalidad','identidad','tea'
    ];
    public function entidad()
    {
        return $this->belongsto(Delista::class,'identidad');
    }
    public function modalidad()
    {
        return $this->belongsto(Delista::class,'idmodalidad');
    }
    public function credito()
    {
        return $this->hasMany(Credito::class,'idtasa_int');
    }
}
