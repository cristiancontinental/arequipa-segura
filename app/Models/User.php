<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Usuario del sistema: solo moderadores y administradores.
 * Los ciudadanos que reportan no tienen cuenta.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROL_ADMINISTRADOR = 'administrador';
    public const ROL_MODERADOR = 'moderador';

    /** @var array<int, string> */
    protected $fillable = ['name', 'email', 'password', 'rol', 'activo'];

    /** @var array<int, string> */
    protected $hidden = ['password', 'remember_token'];

    /** La contraseña se guarda siempre con hash gracias al cast "hashed". */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function esAdministrador(): bool
    {
        return $this->rol === self::ROL_ADMINISTRADOR;
    }

    public function registrosModeracion(): HasMany
    {
        return $this->hasMany(RegistroModeracion::class, 'moderador_id');
    }
}
