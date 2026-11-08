<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Incidente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Pruebas del vencimiento automático y del mapa público. */
class VencimientoTest extends TestCase
{
    use RefreshDatabase;

    private function aprobado(\DateTimeInterface $vence): Incidente
    {
        $categoria = Categoria::firstOrCreate(['nombre' => 'Falsos taxis']);

        return Incidente::create([
            'categoria_id' => $categoria->id,
            'descripcion' => 'Reporte de prueba con texto suficiente.',
            'latitud_aprox' => -16.409,
            'longitud_aprox' => -71.537,
            'estado' => Incidente::ESTADO_APROBADO,
            'aprobado_en' => now()->subDays(8),
            'vence_en' => $vence,
        ]);
    }

    public function test_el_comando_marca_como_vencidos_solo_los_caducados(): void
    {
        $caducado = $this->aprobado(now()->subHour());
        $vigente = $this->aprobado(now()->addDays(3));

        $this->artisan('incidentes:vencer')->assertSuccessful();

        $this->assertSame(Incidente::ESTADO_VENCIDO, $caducado->fresh()->estado);
        $this->assertSame(Incidente::ESTADO_APROBADO, $vigente->fresh()->estado);
    }

    public function test_el_mapa_solo_muestra_aprobados_y_vigentes(): void
    {
        $this->aprobado(now()->subHour());      // caducado (aún aprobado en BD)
        $this->aprobado(now()->addDays(2));     // vigente

        $this->getJson(route('api.alertas'))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonStructure([['lat', 'lng', 'categoria', 'intensidad']]);
    }

    public function test_las_alertas_vencidas_no_se_pueden_marcar_como_inapropiadas(): void
    {
        $incidente = $this->aprobado(now()->addDays(2));
        $incidente->update(['estado' => Incidente::ESTADO_VENCIDO]);

        $this->post(route('alertas.inapropiado', $incidente), ['motivo' => 'Contenido ofensivo'])
            ->assertNotFound();
    }
}
