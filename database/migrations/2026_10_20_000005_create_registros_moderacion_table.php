<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Trazabilidad de las acciones de moderación (aprobar, rechazar, editar, eliminar). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros_moderacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incidente_id')->nullable()->constrained('incidentes')->nullOnDelete();
            $table->foreignId('moderador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('accion', 20);
            $table->string('motivo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_moderacion');
    }
};
