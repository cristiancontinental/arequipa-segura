<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Contacto de emergencia que muestra el módulo de Enlace de Emergencia Crítica. */
class ContactoEmergencia extends Model
{
    /** Situaciones disponibles y su etiqueta para la interfaz. */
    public const SITUACIONES = [
        'incendio' => 'Incendio',
        'violencia_familiar' => 'Violencia familiar',
        'peligro_taxi' => 'Peligro durante un taxi',
        'otra' => 'Otras ayudas',
    ];

    protected $table = 'contactos_emergencia';

    protected $fillable = ['situacion', 'servicio', 'telefono', 'instruccion', 'activo'];

    protected $casts = ['activo' => 'boolean'];
}
