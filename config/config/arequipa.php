<?php

/**
 * Configuración propia de Arequipa Segura.
 *
 * Centraliza las reglas del negocio que pueden cambiar sin tocar el código:
 * vigencia de las alertas, precisión de las coordenadas públicas y los
 * límites geográficos aceptados por el formulario de reportes.
 */
return [

    // Días que un reporte aprobado permanece visible en el mapa preventivo.
    'vigencia_dias' => 7,

    // Decimales con que se guardan las coordenadas (3 decimales ≈ 110 m),
    // para no revelar domicilios exactos.
    'decimales_coordenadas' => 3,

    // Rectángulo geográfico aceptado (zona de Arequipa) para validar el reporte.
    'limites' => [
        'lat_min' => -16.70,
        'lat_max' => -16.15,
        'lng_min' => -71.85,
        'lng_max' => -71.25,
    ],
];
