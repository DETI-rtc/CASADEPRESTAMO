<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Asignameta extends Model
{
    use HasFactory;
     protected $table='meta_usu_mes';   
     protected $fillable = [
    'idpersona','periodo','m1','m2','m3','m4','m5','m6','m7','m8','m9','m10','m11','m12','anual','estado','idsupervisor'
    ];
    public function persona()
    {
        return $this->belongsto(User::class,'idpersona');
    }
}
