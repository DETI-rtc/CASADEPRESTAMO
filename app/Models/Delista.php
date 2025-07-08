<?php

namespace App\Models;
use App\Models\Lista;
use App\Models\Tasainteres;
use App\Models\Persona;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\Model;

class Delista extends Model
{
    protected $table='lista_deta';
    protected $fillable = [
        'nomdelista','idlista',
    ];
    public function lista()
    {
        return $this->belongsto(Lista::class,'idlista');
    }

    public function tasaentidad()
    {
        return $this->hasMany(Tasainteres::class,'id');
    }
    public function tasamodalidad()
    {
        return $this->hasMany(Tasainteres::class,'id');
    }
    public function perentidad()
    {
        return $this->hasMany(Persona::class,'id');
    }
    public function permodalidad()
    {
        return $this->hasMany(Persona::class,'id');
    }

    public function empresatipo()
    {
        return $this->hasMany(Empresa::class,'id');
    }

}
