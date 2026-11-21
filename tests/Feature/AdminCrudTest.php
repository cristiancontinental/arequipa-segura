<?php

namespace Tests\Feature;

use App\Models\ContactoEmergencia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Pruebas del CRUD del administrador: contactos de emergencia y usuarios. */
class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['rol' => 'administrador', 'activo' => true]);
    }

    private function moderador(): User
    {
        return User::factory()->create(['rol' => 'moderador', 'activo' => true]);
    }

    public function test_el_administrador_crea_un_contacto_de_emergencia(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.contactos.store'), [
                'situacion' => 'incendio',
                'servicio' => 'Bomberos',
                'telefono' => '116',
                'activo' => '1',
            ])
            ->assertRedirect(route('admin.contactos.index'));

        $this->assertDatabaseHas('contactos_emergencia', [
            'servicio' => 'Bomberos',
            'telefono' => '116',
        ]);
    }

    public function test_el_administrador_actualiza_un_contacto_de_emergencia(): void
    {
        $contacto = ContactoEmergencia::create([
            'situacion' => 'incendio',
            'servicio' => 'Bomberos',
            'telefono' => '116',
            'activo' => true,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.contactos.update', $contacto), [
                'situacion' => 'incendio',
                'servicio' => 'Bomberos Arequipa',
                'telefono' => '054213333',
                'activo' => '1',
            ])
            ->assertRedirect(route('admin.contactos.index'));

        $this->assertDatabaseHas('contactos_emergencia', [
            'id' => $contacto->id,
            'servicio' => 'Bomberos Arequipa',
            'telefono' => '054213333',
        ]);
    }

    public function test_el_administrador_elimina_un_contacto_de_emergencia(): void
    {
        $contacto = ContactoEmergencia::create([
            'situacion' => 'peligro_taxi',
            'servicio' => 'Policía Nacional',
            'telefono' => '105',
            'activo' => true,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.contactos.destroy', $contacto))
            ->assertRedirect();

        $this->assertDatabaseMissing('contactos_emergencia', [
            'id' => $contacto->id,
        ]);
    }

    public function test_rechaza_telefono_invalido_al_crear_contacto(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.contactos.store'), [
                'situacion' => 'otra',
                'servicio' => 'Ambulancia SAMU',
                'telefono' => 'abc',
                'activo' => '1',
            ])
            ->assertSessionHasErrors('telefono')
            ->assertSessionDoesntHaveErrors('situacion');
    }

    public function test_el_administrador_crea_un_usuario_moderador(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.usuarios.store'), [
                'name' => 'Nuevo Moderador',
                'email' => 'moderador.nuevo@arequipasegura.pe',
                'rol' => 'moderador',
                'password' => 'ClaveSegura123',
                'activo' => '1',
            ])
            ->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'moderador.nuevo@arequipasegura.pe',
            'rol' => 'moderador',
        ]);
    }

    public function test_un_moderador_no_puede_crear_contactos(): void
    {
        $this->actingAs($this->moderador())
            ->post(route('admin.contactos.store'), [
                'situacion' => 'incendio',
                'servicio' => 'Bomberos',
                'telefono' => '116',
                'activo' => '1',
            ])
            ->assertForbidden();
    }
}