<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Avisos de contenido inapropiado enviados por los visitantes sobre una alerta publicada. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes_contenido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incidente_id')->constrained('incidentes')->cascadeOnDelete();
            $table->string('motivo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes_contenido');
    }
};
