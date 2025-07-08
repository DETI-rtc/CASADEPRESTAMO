<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pabellon extends Model
{
    use HasFactory;
    protected $table='cem_pabellon';
    protected $fillable = [
        'nombre',
        'topopab',
        'fecini',
        'caras',
        'calidad',
        'fecfin',
        'codigo',
        'imagen',
        
    ];
    public function arraydetalle() {
        return $this->hasMany('App\Models\Caraspabellon', 'idpabe');
    }

}
