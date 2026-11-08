<?php

namespace App\Services;

use App\Models\Evidencia;
use App\Models\Incidente;
use App\Repositories\Contracts\EvidenciaRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Gestiona las fotografías adjuntas: las guarda en un disco privado (fuera
 * de public/) con un nombre generado por el sistema y conserva solo los
 * metadatos en la base de datos.
 */
class EvidenciaService
{
    public function __construct(private EvidenciaRepositoryInterface $evidencias)
    {
    }

    /** Guarda el archivo de forma privada y registra sus metadatos. */
    public function guardar(Incidente $incidente, UploadedFile $archivo): Evidencia
    {
        $tamano = (int) $archivo->getSize();
        $tipo = $archivo->extension() ?: 'bin';
        $ruta = Storage::disk('local')->putFile('evidencias', $archivo);

        return $this->evidencias->crear([
            'incidente_id' => $incidente->id,
            'ruta_privada' => $ruta,
            'tipo_archivo' => $tipo,
            'tamano' => $tamano,
        ]);
    }

    /** Borra los archivos y metadatos de un reporte (antes de eliminarlo). */
    public function eliminarDeIncidente(Incidente $incidente): void
    {
        foreach ($incidente->evidencias as $evidencia) {
            Storage::disk('local')->delete($evidencia->ruta_privada);
            $this->evidencias->eliminar($evidencia);
        }
    }

    /** Respuesta HTTP con el archivo, solo para usuarios autorizados (lo valida la ruta). */
    public function respuesta(Evidencia $evidencia): StreamedResponse
    {
        return Storage::disk('local')->response(
            $evidencia->ruta_privada,
            null,
            ['X-Content-Type-Options' => 'nosniff']
        );
    }
}
