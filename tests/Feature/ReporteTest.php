<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Incidente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Pruebas del formulario público de reportes (sin cuenta). */
class ReporteTest extends TestCase
{
    use RefreshDatabase;

    private function datosValidos(Categoria $categoria): array
    {
        return [
            'categoria_id' => $categoria->id,
            'descripcion' => 'Un vehículo sin placa ofreció servicio de taxi.',
            'latitud' => -16.4093421,
            'longitud' => -71.5371234,
        ];
    }

    public function test_un_ciudadano_envia_un_reporte_sin_cuenta(): void
    {
        $categoria = Categoria::create(['nombre' => 'Falsos taxis']);

        $this->post(route('reportes.store'), $this->datosValidos($categoria))
            ->assertRedirect(route('reportes.create'));

        $incidente = Incidente::firstOrFail();
        $this->assertSame(Incidente::ESTADO_PENDIENTE, $incidente->estado);
        // Las coordenadas se guardan redondeadas (3 decimales).
        $this->assertEquals(-16.409, $incidente->latitud_aprox);
        $this->assertEquals(-71.537, $incidente->longitud_aprox);
    }

    public function test_el_reporte_pendiente_no_aparece_en_el_mapa_publico(): void
    {
        $categoria = Categoria::create(['nombre' => 'Falsos taxis']);
        $this->post(route('reportes.store'), $this->datosValidos($categoria));

        $this->getJson(route('api.alertas'))->assertOk()->assertJsonCount(0);
    }

    public function test_el_servidor_rechaza_datos_invalidos(): void
    {
        $this->post(route('reportes.store'), [
            'categoria_id' => 999,
            'descripcion' => str_repeat('a', 600),
            'latitud' => 10,
            'longitud' => -71.5,
        ])->assertSessionHasErrors(['categoria_id', 'descripcion', 'latitud']);

        $this->assertSame(0, Incidente::count());
    }

    public function test_no_se_aceptan_archivos_que_no_son_imagenes(): void
    {
        $categoria = Categoria::create(['nombre' => 'Falsos taxis']);
        $archivo = \Illuminate\Http\UploadedFile::fake()->create('virus.php', 10, 'application/x-php');

        $this->post(route('reportes.store'), $this->datosValidos($categoria) + ['evidencia' => $archivo])
            ->assertSessionHasErrors('evidencia');
    }
}
