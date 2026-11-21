# Arequipa Segura

Aplicación web (Laravel 12, arquitectura MVC) de prevención ciudadana y acceso rápido a servicios de
emergencia en Arequipa. Proyecto del curso de **Programación Web** (Universidad Continental), Consolidado 2.

- **Mapa Preventivo:** los ciudadanos reportan **sin cuenta**; un moderador aprueba o rechaza; los reportes
  aprobados se muestran como mapa de calor y vencen a los 7 días.
- **Enlace de Emergencia Crítica:** botones de llamada (`tel:`) por tipo de emergencia y botón SOS que envía la
  ubicación **una sola vez** a un contacto de confianza (WhatsApp o SMS). Estos casos no se publican en el mapa.

**Equipo:** Llacho Cárdenas Leyna Joneth y Mullisaca Guzman Cristian Josue.

## Requisitos

- PHP 8.2 o superior (con las extensiones habituales de Laravel: mbstring, openssl, pdo_mysql, fileinfo, gd o similar).
- Composer 2.
- MySQL 8 o MariaDB (en Windows, **Laragon Full** trae PHP, Composer y MySQL).

## Instalación (quien revise el proyecto)

El ZIP no incluye `vendor/` ni el archivo `.env`, por lo que hay que generarlos:

```bash
# 1. Instalar las dependencias de PHP
composer install

# 2. Crear el archivo de entorno y la clave de la aplicación
copy .env.example .env          # en Linux/macOS: cp .env.example .env
php artisan key:generate

# 3. Crear la base de datos vacía "arequipa_segura" en MySQL
#    (por ejemplo desde phpMyAdmin o con:  mysql -u root -e "CREATE DATABASE arequipa_segura")
#    Si tu usuario o contraseña de MySQL son distintos, edítalos en .env (DB_USERNAME, DB_PASSWORD).

# 4. Crear las tablas y cargar los datos iniciales
php artisan migrate --seed

# 5. Ejecutar la aplicación
php artisan serve               # http://127.0.0.1:8000
php artisan schedule:work       # (otra terminal) ejecuta el vencimiento automático de reportes
```

Para probar sin MySQL puedes usar SQLite: en `.env` cambia `DB_CONNECTION=sqlite`, borra las demás líneas `DB_*`
y crea el archivo vacío `database/database.sqlite`.

### Cuentas de prueba (las crea el seeder; cámbialas antes de publicar la aplicación)

| Rol | Correo | Contraseña |
|---|---|---|
| Administrador | admin@arequipasegura.test | Admin#2026 |
| Moderador | moderador@arequipasegura.test | Moderador#2026 |

Los reportes de ejemplo solo se crean si `APP_ENV=local`.

### Páginas principales

| Dirección | Qué es |
|---|---|
| `/` | Inicio (3 opciones) |
| `/mapa` | Mapa preventivo y lista de alertas vigentes |
| `/reportar` | Formulario de reporte (sin cuenta) |
| `/emergencias` | Botones de llamada y botón SOS |
| `/ingresar` | Inicio de sesión de moderadores y administradores |
| `/panel/reportes` | Moderación de reportes (moderador o administrador) |
| `/admin/categorias`, `/admin/contactos`, `/admin/usuarios` | CRUD del administrador |

## Pruebas automáticas

```bash
php artisan test
```

Son 24 pruebas (usan SQLite en memoria; no tocan la base de datos MySQL). Cubren: envío de reportes sin cuenta,
validación en el servidor, rechazo de archivos que no son imágenes, redirección al login, autorización por roles,
usuarios desactivados, moderación (aprobar, rechazar, eliminar), CRUD de categorías, vencimiento a 7 días, mapa solo
con alertas vigentes, prevención de XSS (texto con `<script>` escapado), entrada con comilla (SQL) y módulo de
emergencias.

## Requisitos de la consigna y dónde se cumplen

| Requisito | Dónde |
|---|---|
| Framework moderno | Laravel 12 |
| Arquitectura MVC | Modelos `app/Models`, vistas `resources/views` (Blade), controladores `app/Http/Controllers`; capa adicional de servicios (`app/Services`) y repositorios (`app/Repositories`) |
| CRUD completo | Reportes (`Panel/ModeracionController`), categorías, contactos de emergencia y usuarios (`Admin/*`) |
| Autenticación y autorización | `Auth/LoginController`, middleware `EnsureRole` (roles `moderador` y `administrador`); los ciudadanos no necesitan cuenta |
| Diseño responsive | `public/css/app.css` (mobile-first, botones de 48 px o más) |
| Seguridad básica | Eloquent y consultas parametrizadas (SQL); Blade escapa la salida con `{{ }}` (XSS); CSRF en formularios; validación en el servidor; `throttle` en reportes y login; contraseñas con hash; archivos privados con tipo y tamaño validados |
| Documentación técnica | Comentarios PHPDoc en cada clase y método, este README y el informe |

## Estructura

```
app/Models/                 Modelos Eloquent (Incidente, Categoria, Evidencia, ContactoEmergencia, ...)
app/Repositories/           Interfaces (Contracts) e implementaciones Eloquent
app/Services/               IncidenteService, ModeracionService, EvidenciaService, VencimientoService, EmergenciaService
app/Http/Controllers/       Públicos, Auth, Panel (moderación) y Admin (CRUD)
app/Http/Middleware/        EnsureRole (autorización por rol)
app/Http/Requests/          Validación (StoreReporteRequest, UpdateReporteRequest)
app/Console/Commands/       incidentes:vencer
config/arequipa.php         Vigencia (7 días), decimales de coordenadas y límites geográficos
database/migrations/        Tablas
resources/views/            Vistas Blade
public/js/                  mapa.js, reportar.js, sos.js
tests/Feature/              Pruebas automáticas
```

## Producción (resumen)

1. `APP_ENV=production`, `APP_DEBUG=false` (sin mensajes de error detallados) y HTTPS.
2. Cron único: `* * * * * cd /ruta/proyecto && php artisan schedule:run >> /dev/null 2>&1`
3. Cambiar las contraseñas del seeder, verificar los números de emergencia desde el panel de administración y
   configurar copias de seguridad de la base de datos y de `storage/app/private`.

## Referencias y licencias

- Laravel, https://laravel.com (MIT).
- Leaflet 1.9.4, https://leafletjs.com (BSD de 2 cláusulas), cargado desde cdnjs.
- Leaflet.heat 0.2.0, https://github.com/Leaflet/Leaflet.heat (BSD de 2 cláusulas, según su repositorio).
- Mapa base y datos: © colaboradores de OpenStreetMap, https://www.openstreetmap.org/copyright (ODbL); la atribución
  aparece en el mapa.
- Código del proyecto: licencia MIT (archivo `LICENSE`).

## Flujo de trabajo con Git del equipo

- Una rama por funcionalidad o tarea (`feature/...`, `docs/...`, `fix/...`) y *pull request* revisado por el otro
  integrante antes de integrar a `main`.
- Mensajes de commit descriptivos en español.
- Cada integrante trabaja con su propio usuario de Git (`git config user.name` y `user.email`).
- Para ver las contribuciones: `git shortlog -sn` y `git log --oneline --author="Nombre"`.
