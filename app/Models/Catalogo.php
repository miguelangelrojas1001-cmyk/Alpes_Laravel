<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $table = 'catalogo';
    protected $primaryKey = 'idcatalogo';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'idcatalogo', 'idcatalogo');
    }
}
