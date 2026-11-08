<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reportes ciudadanos. No guarda nombre, correo, teléfono ni IP de quien
 * reporta: solo la categoría, la descripción y una ubicación aproximada.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias')->restrictOnDelete();
            $table->text('descripcion');
            $table->decimal('latitud_aprox', 9, 6);
            $table->decimal('longitud_aprox', 9, 6);
            // pendiente | aprobado | rechazado | vencido
            $table->string('estado', 15)->default('pendiente')->index();
            $table->timestamp('aprobado_en')->nullable();
            $table->timestamp('vence_en')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidentes');
    }
};
