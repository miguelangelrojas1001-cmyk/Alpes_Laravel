<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domiciliario extends Model
{
    protected $table = 'domiciliario';
    protected $primaryKey = 'iddomiciliario';
    public $timestamps = false;

    protected $fillable = [
        'idpersona',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'idpersona', 'idpersona');
    }
}
