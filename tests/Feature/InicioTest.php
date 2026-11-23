<?php

namespace Tests\Feature;

use App\Models\ContactoEmergencia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Pruebas de la pantalla de inicio y de las llamadas rápidas (1 toque). */
class InicioTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_inicio_muestra_llamadas_rapidas_con_enlace_tel(): void
    {
        ContactoEmergencia::create(['situacion' => 'incendio', 'servicio' => 'Bomberos', 'telefono' => '116', 'activo' => true]);
        ContactoEmergencia::create(['situacion' => 'peligro_taxi', 'servicio' => 'Policía Nacional', 'telefono' => '105', 'activo' => true]);

        $this->get(route('inicio'))
            ->assertOk()
            ->assertSee('tel:116', false)
            ->assertSee('tel:105', false);
    }

    public function test_el_inicio_no_muestra_contactos_inactivos(): void
    {
        ContactoEmergencia::create(['situacion' => 'incendio', 'servicio' => 'Oculto', 'telefono' => '999', 'activo' => false]);

        $this->get(route('inicio'))
            ->assertOk()
            ->assertDontSee('tel:999', false);
    }

    public function test_el_inicio_funciona_sin_contactos_configurados(): void
    {
        $this->get(route('inicio'))
            ->assertOk()
            ->assertSee('Reportar una situación');
    }
}