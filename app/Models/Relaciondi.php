<?php

namespace App\Models;
use App\Models\Delista;
use App\Models\Credito;
use Illuminate\Database\Eloquent\Model;

class Relaciondi extends Model
{
    protected $table='pre_rdi';
   
     protected $fillable = [
    'idmodalidad','identidad','rci_max'
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
        return $this->hasMany(Credito::class,'idrdi');
    }
}
