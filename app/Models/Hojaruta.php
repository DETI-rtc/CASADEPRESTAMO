<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetalleHojaruta;

class Hojaruta extends Model
{
    use HasFactory;
    protected $table='hoja_ruta';
    protected $fillable = [
        'idanalista','fecha', 'estado',
    ];
    public function arrayDetalle()
    {
        return $this->hasMany(Detallehojaruta::class,'idhoja');
    }
}
