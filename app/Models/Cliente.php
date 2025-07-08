<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $table='cliente';
    protected $fillable = [
        'tipo_documento_id',
        'numerodoc',
        'nombre',
        'razon_social',
        'direccion',
        'country_id',
        'department_id',
        'province_id',
        'district_id',
        'direccion',
        'telefono',
        'email',
        'nombre_cont',
        'telefono_cont',
    ];

    public function creditos (){
        return $this->hasMany('App\Models\Credito','idcliente');
    }

}
