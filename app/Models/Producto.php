<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'idproducto';
    public $timestamps = false;

    protected $fillable = [
        'idcatalogo',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'disponible',
    ];

    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class, 'idcatalogo', 'idcatalogo');
    }
}
