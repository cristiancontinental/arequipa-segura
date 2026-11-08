<?php

namespace Tests\Feature;

use App\Models\ContactoEmergencia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Pruebas del módulo de Enlace de Emergencia Crítica. */
class EmergenciaTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pantalla_muestra_solo_contactos_activos_con_enlace_tel(): void
    {
        ContactoEmergencia::create(['situacion' => 'incendio', 'servicio' => 'Bomberos', 'telefono' => '116', 'activo' => true]);
        ContactoEmergencia::create(['situacion' => 'incendio', 'servicio' => 'Oculto', 'telefono' => '999', 'activo' => false]);

        $this->get(route('emergencias'))
            ->assertOk()
            ->assertSee('tel:116', false)
            ->assertDontSee('tel:999', false);
    }

    public function test_el_modulo_de_emergencias_no_guarda_nada_en_el_mapa(): void
    {
        $this->get(route('emergencias'))->assertOk();
        $this->assertDatabaseCount('incidentes', 0);
    }
}
