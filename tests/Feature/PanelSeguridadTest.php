<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Incidente;
use App\Models\RegistroModeracion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Pruebas de autenticación, autorización por roles, moderación y XSS. */
class PanelSeguridadTest extends TestCase
{
    use RefreshDatabase;

    private function usuario(string $rol, bool $activo = true): User
    {
        return User::factory()->create(['rol' => $rol, 'activo' => $activo]);
    }

    private function incidente(string $estado = 'pendiente', string $texto = 'Descripción de prueba larga.'): Incidente
    {
        $categoria = Categoria::firstOrCreate(['nombre' => 'Falsos taxis']);

        return Incidente::create([
            'categoria_id' => $categoria->id,
            'descripcion' => $texto,
            'latitud_aprox' => -16.409,
            'longitud_aprox' => -71.537,
            'estado' => $estado,
            'aprobado_en' => $estado === 'aprobado' ? now() : null,
            'vence_en' => $estado === 'aprobado' ? now()->addDays(7) : null,
        ]);
    }

    public function test_un_invitado_es_enviado_al_login(): void
    {
        $this->get(route('panel.reportes.index'))->assertRedirect(route('login'));
    }

    public function test_un_moderador_no_accede_a_la_administracion(): void
    {
        $this->actingAs($this->usuario('moderador'))
            ->get(route('admin.usuarios.index'))
            ->assertForbidden();
    }

    public function test_un_administrador_accede_a_la_administracion(): void
    {
        $this->actingAs($this->usuario('administrador'))
            ->get(route('admin.usuarios.index'))
            ->assertOk();
    }

    public function test_un_usuario_desactivado_no_puede_ingresar(): void
    {
        $usuario = $this->usuario('moderador', false);

        $this->post(route('login.store'), ['email' => $usuario->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_el_moderador_aprueba_y_se_fija_el_vencimiento_y_el_registro(): void
    {
        $moderador = $this->usuario('moderador');
        $incidente = $this->incidente();

        $this->actingAs($moderador)
            ->post(route('panel.reportes.aprobar', $incidente))
            ->assertRedirect();

        $incidente->refresh();
        $this->assertSame(Incidente::ESTADO_APROBADO, $incidente->estado);
        $this->assertTrue($incidente->vence_en->greaterThan(now()->addDays(6)));
        $this->assertSame(1, RegistroModeracion::where('accion', 'aprobado')->count());
    }

    public function test_el_moderador_rechaza_y_elimina_un_reporte(): void
    {
        $moderador = $this->usuario('moderador');
        $incidente = $this->incidente();

        $this->actingAs($moderador)->post(route('panel.reportes.rechazar', $incidente), ['motivo' => 'Duplicado']);
        $this->assertSame(Incidente::ESTADO_RECHAZADO, $incidente->fresh()->estado);

        $this->actingAs($moderador)->delete(route('panel.reportes.destroy', $incidente));
        $this->assertNull(Incidente::find($incidente->id));
    }

    public function test_el_administrador_crea_una_categoria(): void
    {
        $this->actingAs($this->usuario('administrador'))
            ->post(route('admin.categorias.store'), ['nombre' => 'Otra categoría', 'activa' => '1'])
            ->assertRedirect(route('admin.categorias.index'));

        $this->assertDatabaseHas('categorias', ['nombre' => 'Otra categoría']);
    }

    public function test_el_texto_con_script_se_muestra_escapado_y_no_se_ejecuta(): void
    {
        $this->incidente('aprobado', '<script>alert(1)</script> texto de prueba');

        $this->get(route('mapa'))
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_una_consulta_con_comilla_no_altera_la_base_de_datos(): void
    {
        $this->incidente('aprobado');

        // Entrada típica de inyección SQL en el filtro: se trata como dato, no como código.
        $this->actingAs($this->usuario('moderador'))
            ->get(route('panel.reportes.index', ['estado' => "' OR '1'='1"]))
            ->assertOk();

        $this->assertSame(1, Incidente::count());
    }
}
