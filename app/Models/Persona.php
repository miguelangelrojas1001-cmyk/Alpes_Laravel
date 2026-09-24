<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Persona extends Authenticatable
{
    protected $table = 'persona';
    protected $primaryKey = 'idpersona';
    public $timestamps = false;

    protected $fillable = [
        'idrol',
        'nombre',
        'correo',
        'contrasena',
    ];

    protected $hidden = [
        'contrasena',
    ];

    /**
     * Get the password column for authentication.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getRolNombreAttribute(): ?string
    {
        return $this->rol ? $this->rol->nombre : null;
    }

    public function hasRole(string $role): bool
    {
        return $this->rol && strtolower($this->rol->nombre) === strtolower($role);
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idrol', 'idrol');
    }

    public function administrador()
    {
        return $this->hasOne(Administrador::class, 'idpersona', 'idpersona');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'idpersona', 'idpersona');
    }

    public function domiciliario()
    {
        return $this->hasOne(Domiciliario::class, 'idpersona', 'idpersona');
    }
}
