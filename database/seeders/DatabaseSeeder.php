<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\ContactoEmergencia;
use App\Models\Incidente;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Datos iniciales: usuarios de prueba, categorías y contactos de emergencia.
 * Los reportes de ejemplo solo se crean en entorno local (APP_ENV=local).
 *
 * IMPORTANTE: cambia estas contraseñas antes de publicar la aplicación y
 * verifica que los números de emergencia sigan vigentes.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@arequipasegura.test'], [
            'name' => 'Administrador',
            'password' => 'Admin#2026',
            'rol' => User::ROL_ADMINISTRADOR,
            'activo' => true,
        ]);
        User::updateOrCreate(['email' => 'moderador@arequipasegura.test'], [
            'name' => 'Moderador',
            'password' => 'Moderador#2026',
            'rol' => User::ROL_MODERADOR,
            'activo' => true,
        ]);

        $taxis = Categoria::firstOrCreate(['nombre' => 'Falsos taxis']);
        $robos = Categoria::firstOrCreate(['nombre' => 'Robos en viviendas']);
        $incendios = Categoria::firstOrCreate(['nombre' => 'Incendios']);

        if (ContactoEmergencia::count() === 0) {
                        ContactoEmergencia::insert([
                ['situacion' => 'incendio', 'servicio' => 'Bomberos', 'telefono' => '116',
                    'instruccion' => 'Aléjate del lugar y llama a los Bomberos.', 'activo' => true],
                ['situacion' => 'violencia_familiar', 'servicio' => 'Línea 100 (Ministerio de la Mujer)', 'telefono' => '100',
                    'instruccion' => 'Si estás en peligro inmediato, busca un lugar seguro y llama.', 'activo' => true],
                ['situacion' => 'peligro_taxi', 'servicio' => 'Policía Nacional', 'telefono' => '105',
                    'instruccion' => 'Si es posible, comparte tu ubicación con alguien de confianza (botón SOS).', 'activo' => true],
                ['situacion' => 'robo_vivienda', 'servicio' => 'Policía Nacional', 'telefono' => '105',
                    'instruccion' => 'No te enfrentes a los delincuentes; resguárdate y llama al 105.', 'activo' => true],
                ['situacion' => 'otra', 'servicio' => 'Salud (SAMU)', 'telefono' => '106',
                    'instruccion' => 'Para emergencias médicas.', 'activo' => true],
            ]);

        }

        if (app()->environment('local') && Incidente::count() === 0) {
            $ejemplos = [
                [$taxis, -16.380, -71.560, 'Un auto sin placa visible ofreció llevarme y cambió de ruta.'],
                [$taxis, -16.430, -71.500, 'Conductor que no coincidía con la foto de la aplicación de transporte.'],
                [$robos, -16.392, -71.547, 'Intentaron forzar la puerta de una casa que estaba sola durante el día.'],
                [$robos, -16.395, -71.520, 'Vecinos comentan robos recientes en esta cuadra.'],
                [$taxis, -16.370, -71.540, 'Vehículo sin identificación pidiendo a pasajeros que suban rápido.'],
            ];

            foreach ($ejemplos as [$categoria, $lat, $lng, $texto]) {
                Incidente::create([
                    'categoria_id' => $categoria->id,
                    'descripcion' => $texto,
                    'latitud_aprox' => $lat,
                    'longitud_aprox' => $lng,
                    'estado' => Incidente::ESTADO_APROBADO,
                    'aprobado_en' => now()->subDays(1),
                    'vence_en' => now()->addDays(6),
                ]);
            }
        }
    }
}
