<?php

namespace App\Models;
use App\Models\Delista;
use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    
    protected $fillable = [
        'nomlista',
    ];
    public function detalle()
    {
        return $this->hasMany(Delista::class,'idlista');
    }
}
