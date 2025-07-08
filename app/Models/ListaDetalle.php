<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaDetalle extends Model
{
    protected $table='lista_deta';
    protected $fillable = [
        'nomdelista',
        'idlista',
        'estado',
    ];
}
