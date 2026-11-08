<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Contactos del módulo de emergencias; los gestiona el administrador (CRUD). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contactos_emergencia', function (Blueprint $table) {
            $table->id();
            // incendio | violencia_familiar | peligro_taxi | otra
            $table->string('situacion', 30);
            $table->string('servicio', 80);
            $table->string('telefono', 20);
            $table->string('instruccion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contactos_emergencia');
    }
};
