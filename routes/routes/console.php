<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
| Vencimiento automático de los reportes (vigencia de 7 días).
| En producción se activa con un único cron: * * * * * php artisan schedule:run
| En desarrollo: php artisan schedule:work
*/
Schedule::command('incidentes:vencer')->hourly();
