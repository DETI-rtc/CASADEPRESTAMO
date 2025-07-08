<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caraspabellon extends Model
{
    use HasFactory;
    protected $table='cem_pabelloncaras';
    protected $fillable = [
        'idpabe',
        'codcara',
        'fila',
        'columna',
        'estado',
    ];
    public function pabellon()
    {
        return $this->belongsto('App\Models\Pabellon','idpabe','idpabe');
    }
}
