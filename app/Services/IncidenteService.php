<?php

namespace App\Services;

use App\Models\Incidente;
use App\Repositories\Contracts\IncidenteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Reglas de negocio de los reportes: registro, consulta para el mapa,
 * edición y eliminación.
 */
class IncidenteService
{
    public function __construct(
        private IncidenteRepositoryInterface $incidentes,
        private EvidenciaService $evidencias
    ) {
    }

    /**
     * Registra un reporte ciudadano. Nace "pendiente" y sus coordenadas se
     * redondean para no revelar la ubicación exacta.
     *
     * @param array{categoria_id:int,descripcion:string,latitud:float|string,longitud:float|string} $datos
     */
    public function registrar(array $datos, ?UploadedFile $archivo = null): Incidente
    {
        $decimales = (int) config('arequipa.decimales_coordenadas', 3);

        return DB::transaction(function () use ($datos, $archivo, $decimales) {
            $incidente = $this->incidentes->crear([
                'categoria_id' => $datos['categoria_id'],
                'descripcion' => trim($datos['descripcion']),
                'latitud_aprox' => round((float) $datos['latitud'], $decimales),
                'longitud_aprox' => round((float) $datos['longitud'], $decimales),
                'estado' => Incidente::ESTADO_PENDIENTE,
            ]);

            if ($archivo !== null) {
                $this->evidencias->guardar($incidente, $archivo);
            }

            return $incidente;
        });
    }

    /** Reportes aprobados y vigentes (para el mapa y la lista pública). */
    public function vigentes(): Collection
    {
        return $this->incidentes->vigentes();
    }

    /**
     * Puntos para el mapa de calor: solo coordenadas aproximadas y categoría.
     *
     * @return array<int, array{lat:float,lng:float,categoria:string,intensidad:float}>
     */
    public function puntosParaMapa(): array
    {
        return $this->incidentes->vigentes()->map(fn (Incidente $i) => [
            'lat' => $i->latitud_aprox,
            'lng' => $i->longitud_aprox,
            'categoria' => $i->categoria->nombre,
            'intensidad' => 0.6,
        ])->values()->all();
    }

    public function paginar(?string $estado): LengthAwarePaginator
    {
        $estado = in_array($estado, Incidente::ESTADOS, true) ? $estado : null;

        return $this->incidentes->paginar($estado);
    }

    /** Actualiza categoría y descripción (corrección hecha por un moderador). */
    public function actualizar(Incidente $incidente, array $datos): Incidente
    {
        return $this->incidentes->actualizar($incidente, [
            'categoria_id' => $datos['categoria_id'],
            'descripcion' => trim($datos['descripcion']),
        ]);
    }

    /** Elimina el reporte y sus archivos adjuntos. */
    public function eliminar(Incidente $incidente): void
    {
        DB::transaction(function () use ($incidente) {
            $this->evidencias->eliminarDeIncidente($incidente);
            $this->incidentes->eliminar($incidente);
        });
    }
}
