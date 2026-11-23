# Prueba de usabilidad de Arequipa Segura

- **Fecha(s) de la prueba:** 23/11/2026
- **Quién aplicó la prueba:** Cristian
- **Quién revisó este documento:** [nombre] Leyna (pendiente)
- **Versión probada:** commit `35119fe`
- **Dispositivo y navegador:** Android 13, Chrome
- **Participantes:** 3 personas, anónimas (P1, P2 y P3), con consentimiento verbal. No se guardan nombres ni datos personales.

## Objetivo

Medir con usuarios reales dos de los indicadores de impacto del informe:

1. **Rapidez de acceso a la ayuda:** máximo 2 toques y 10 segundos desde la pantalla de inicio hasta el botón de llamada.
2. **Facilidad de uso:** al menos 80 % de los usuarios completa un reporte anónimo sin ayuda en menos de 2 minutos.

(El indicador 2, alertas moderadas y vigentes, se verifica con las pruebas automáticas y con el registro de moderación.)

## Procedimiento

1. Se reinició la base de datos de demostración con `php artisan migrate:fresh --seed`.
2. Cada participante usó la aplicación en un celular, sin explicaciones previas.
3. Tarea 1: «Imagina que hay un incendio. Desde la pantalla de inicio, llega al botón para llamar a los bomberos.» Se contaron los toques y se cronometró el tiempo hasta pulsar el botón de llamada.
4. Tarea 2: «Viste un auto sospechoso ofreciendo taxi. Envía un reporte.» Se cronometró el tiempo hasta ver el mensaje de confirmación y se anotó si necesitó ayuda.
5. Al final se preguntó: «¿Qué tan fácil fue usar la aplicación?» (1 = muy difícil, 5 = muy fácil) y qué cambiaría.

## Resultados

| Participante | Tarea 1: toques | Tarea 1: segundos | ¿Cumple tarea 1? (≤ 2 toques y ≤ 10 s) | Tarea 2: segundos | ¿Necesitó ayuda? | ¿Cumple tarea 2? (sin ayuda y < 120 s) | Facilidad (1 a 5) | Comentario |
|---|---|---|---|---|---|---|---|---|
| P1 |20|192,67|no|136,27|si|no|2|Complicado por no conocer los sitios web|
| P2 |8|87,80|no|372,78|no|no|2|Falta de imágenes, no puedo identificar qué es cada cosa|
| P3 |12|32,01|no|365|no|no|3|Integrar opciones de elección para facilitar la parte de descripción y no perder mucho tiempo escribiendo|

## Indicadores

| Indicador | Meta | Resultado | ¿Se cumplió? |
|---|---|---|---|
| 1. Rapidez de acceso a la ayuda | Máximo 2 toques y 10 segundos | 0 de 3 participantes (0 %). Mediana: 12 toques y 87,80 segundos | No |
| 3. Facilidad de uso y participación | Al menos 80 % completa un reporte sin ayuda en menos de 2 minutos | 0 de 3 participantes (0 %). | no |
| Facilidad percibida  | Informativo | Promedio: 2,33 de 5.| Informativo |

## Problemas observados

- Los participantes presentaron dificultades para encontrar y utilizar algunas opciones de la aplicación.
- Se identificó falta de imágenes que permitan reconocer visualmente las diferentes funciones.
- El proceso de descripción de los reportes puede resultar lento debido a que requiere escribir manualmente.
- Un participante manifestó dificultades por no conocer los sitios web disponibles.

## Mejoras propuestas

- Incorporar imágenes e iconos representativos para facilitar la identificación de las funciones.
- Mejorar la organización visual y la navegación de la aplicación.
- Incorporar opciones de selección predefinidas para facilitar la descripción de los reportes.
- Simplificar el proceso de envío de reportes para reducir el tiempo necesario para completar la tarea.
- Realizar nuevas pruebas de usabilidad después de implementar las mejoras.

## Limitaciones

- La muestra es pequeña (3 personas), por lo que los resultados son orientativos y no representan a toda la población.
- La ubicación se marcó tocando el mapa; el botón «Usar mi ubicación» no se probó porque el navegador solo lo permite en conexiones seguras (HTTPS).
- Los tiempos registrados necesitan confirmación de formato para realizar los cálculos definitivos.
