<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    protected $table = 'administrador';
    protected $primaryKey = 'idadministrador';
    public $timestamps = false;

    protected $fillable = [
        'idpersona',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'idpersona', 'idpersona');
    }
}
