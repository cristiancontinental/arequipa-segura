document.addEventListener('DOMContentLoaded', () => {
    const esMapa = document.querySelector('#mapa') !== null;
    const esReporte = document.querySelector('form textarea') !== null;
    const esEmergencia = document.querySelector('a[href^="tel:"]') !== null && !document.querySelector('#tour-mapa');

    const pasosInicio = [
        {
            selector: 'header',
            titulo: '1. Barra de navegación superior',
            texto: 'Toca "Arequipa Segura" para volver al inicio desde cualquier pantalla, o usa los accesos directos para ir al Mapa, Reportar o Emergencias.'
        },
        {
            selector: '#tour-mapa',
            titulo: '2. Consulta el mapa preventivo',
            texto: 'Revisa las zonas con alertas aprobadas de los últimos 7 días en Arequipa antes de salir.'
        },
        {
            selector: '#tour-reportar',
            titulo: '3. Reporta sin crear cuenta',
            texto: 'Si presencias una situación sospechosa, envía un reporte anónimo eligiendo la categoría y el distrito.'
        },
        {
            selector: '#tour-ayuda',
            titulo: '4. Enlace de emergencia y SOS',
            texto: 'Accede a los números de auxilio y envía tu ubicación una sola vez a un contacto de confianza.'
        },
        {
            selector: '#tour-llamada',
            titulo: '5. Llamada rápida directa',
            texto: 'En emergencias críticas, toca cualquiera de estos botones para abrir el marcador de tu teléfono al instante.'
        }
    ];

    const pasosMapa = [
        {
            selector: 'header',
            titulo: '1. Barra de navegación superior',
            texto: 'Toca "Arequipa Segura" para volver al inicio desde cualquier pantalla, o usa los accesos directos para ir al Mapa, Reportar o Emergencias.'
        },
        {
            selector: '#mapa',
            titulo: '2. Mapa de zonas recientes',
            texto: 'Los círculos muestran zonas aproximadas con reportes aprobados en los últimos 7 días. Nunca señalan direcciones exactas.'
        },
        {
            selector: '.leaflet-control-zoom',
            titulo: '3. Controles de acercamiento (+ / -)',
            texto: 'Usa los botones + y - arriba a la izquierda (o tus dedos en el celular) para acercar o alejar tu distrito.'
        },
        {
            selector: 'main ul, main article, .alerta, main section',
            titulo: '4. Lista de alertas vigentes',
            texto: 'Debajo del mapa puedes leer la descripción de cada reporte y cuántos días le quedan antes de vencer automáticamente.'
        },
        {
            selector: 'details',
            titulo: '5. Marcar como inapropiada',
            texto: 'Si ves un reporte falso, ofensivo o con datos personales, abre esta opción para enviarlo a revisión del moderador.'
        }
    ];

    const pasosReporte = [
        {
            selector: 'header',
            titulo: '1. Barra de navegación superior',
            texto: 'Toca "Arequipa Segura" para volver al inicio desde cualquier pantalla, o usa los accesos directos para ir al Mapa, Reportar o Emergencias.'
        },
        {
            selector: 'form fieldset:nth-of-type(1)',
            titulo: '2. Elige el tipo de situación',
            texto: 'Marca la categoría que mejor describe el incidente (como Falsos taxis, Incendios o Robos en viviendas).'
        },
        {
            selector: 'form fieldset:nth-of-type(2)',
            titulo: '3. Cuéntalo en pocas palabras',
            texto: 'Escribe qué pasó de forma breve y clara. Por tu seguridad, evita incluir nombres propios, placas o direcciones exactas.'
        },
        {
            selector: 'form fieldset:nth-of-type(3)',
            titulo: '4. Indica la zona aproximada',
            texto: 'Elige tu distrito en la lista o abre "Afinar en el mapa" para marcar una referencia aproximada.'
        },
        {
            selector: 'form > details',
            titulo: '5. Evidencia opcional (solo imagen)',
            texto: 'Si lo deseas, abre esta opción para adjuntar una foto JPG, PNG o WebP. Los metadatos de ubicación se borran solos.'
        },
        {
            selector: 'form button[type="submit"]',
            titulo: '6. Envía tu reporte anónimo',
            texto: 'Pulsa este botón para enviar el reporte. Un moderador lo revisará antes de publicarlo en el mapa preventivo.'
        }
    ];

    const pasosEmergencia = [
        {
            selector: 'header',
            titulo: '1. Barra de navegación superior',
            texto: 'Toca "Arequipa Segura" para volver al inicio desde cualquier pantalla, o usa los accesos directos para ir al Mapa, Reportar o Emergencias.'
        },
        {
            selector: 'main a[href^="tel:"]',
            titulo: '2. Botones de llamada rápida',
            texto: 'Toca "Llamar ahora" en Bomberos, Línea 100, Policía o SAMU para abrir el marcador telefónico y confirmar la llamada.'
        },
        {
            selector: 'main > section:last-of-type',
            titulo: '3. Compartir ubicación SOS',
            texto: 'Aquí puedes guardar un número de confianza en tu teléfono y compartir tu ubicación GPS una sola vez por WhatsApp o SMS.'
        }

    ];

    let pasos = pasosInicio;
    let claveStorage = 'arequipa_tour_visto';

    if (esMapa) {
        pasos = pasosMapa;
        claveStorage = 'arequipa_tour_mapa_visto';
    } else if (esReporte) {
        pasos = pasosReporte;
        claveStorage = 'arequipa_tour_reporte_visto';
    } else if (esEmergencia) {
        pasos = pasosEmergencia;
        claveStorage = 'arequipa_tour_emergencia_visto';
    }

    const overlay = document.getElementById('tour-overlay');
    const btnIniciar = document.getElementById('btn-iniciar-tour');
    if (!overlay || !btnIniciar) return;

    const btnCerrar = document.getElementById('tour-cerrar');
    const btnAnterior = document.getElementById('tour-anterior');
    const btnSiguiente = document.getElementById('tour-siguiente');
    const elTitulo = document.getElementById('tour-titulo');
    const elTexto = document.getElementById('tour-texto');
    const elContador = document.getElementById('tour-contador');

    let indice = 0;

    function limpiarResaltado() {
        document.querySelectorAll('.tour-resaltado').forEach(el => el.classList.remove('tour-resaltado'));
    }

    function mostrarPaso(i) {
        limpiarResaltado();
        const paso = pasos[i];
        const objetivo = document.querySelector(paso.selector);

        if (objetivo) {
            objetivo.classList.add('tour-resaltado');
            objetivo.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        elTitulo.textContent = paso.titulo;
        elTexto.textContent = paso.texto;
        elContador.textContent = `${i + 1} / ${pasos.length}`;
        btnAnterior.disabled = i === 0;
        btnSiguiente.textContent = i === pasos.length - 1 ? 'Finalizar' : 'Siguiente';
    }

    function abrirTour() {
        indice = 0;
        overlay.hidden = false;
        mostrarPaso(indice);
    }

    function cerrarTour() {
        limpiarResaltado();
        overlay.hidden = true;
        localStorage.setItem(claveStorage, '1');
    }

    btnIniciar.addEventListener('click', abrirTour);
    btnCerrar.addEventListener('click', cerrarTour);

    btnAnterior.addEventListener('click', () => {
        if (indice > 0) {
            indice--;
            mostrarPaso(indice);
        }
    });

    btnSiguiente.addEventListener('click', () => {
        if (indice < pasos.length - 1) {
            indice++;
            mostrarPaso(indice);
        } else {
            cerrarTour();
        }
    });

});
