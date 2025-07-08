<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Difunto extends Model
{
    use HasFactory;
    protected $table='cem_difunto';
    protected $fillable = [
        'tipo_documento_id',
        'numerodoc',
        'nombre_dif',
        'fecnac',
        'gendifu',
        'diredifu',
        'country_id_difu',
        'department_id_difu',
        'province_id_difu',
        'district_id_difu',
        'fecfalle',
        'acta',
        'folio',
        'libro',
        'codigo',
        'codclie',
        'fechorafalle',
        'country_id_fa',
        'department_id_fa',
        'province_id_fa',
        'district_id_fa',
        'edad',
        'estatura',
        'cabello',
        'ojos',
        'otros',
        'codpabe',
        'codtipopab',
        'codnicho',
        'estado',
        'tipofinal',
        'feccrema',
        'peso',
    ];

}
