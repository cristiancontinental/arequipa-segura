/**
 * Formulario de reporte. La ubicación se elige de dos formas:
 *  1. Eligiendo el distrito en una lista (lo más rápido).
 *  2. Opcionalmente, afinando el punto en el mapa o con "Usar mi ubicación".
 * El mapa se crea solo cuando la persona abre "Afinar en el mapa".
 * Librería: Leaflet 1.9.4 (BSD-2-Clause). Mapa base: © OpenStreetMap contributors.
 */
document.addEventListener('DOMContentLoaded', () => {
  const contenedor = document.getElementById('mapa-reporte');
  const inputLat = document.getElementById('latitud');
  const inputLng = document.getElementById('longitud');
  const estado = document.getElementById('ubicacion-estado');
  const formulario = document.getElementById('form-reporte');
  const zona = document.getElementById('zona');
  const detalles = document.getElementById('afinar');
  const aviso = document.getElementById('aviso-formulario');
  if (!contenedor || !formulario) return;

  let mapa = null;
  let marcador = null;

  /** Muestra el marcador en el mapa (si el mapa ya fue creado). */
  function ponerMarcador() {
    if (!mapa || !inputLat.value || !inputLng.value) return;
    const lat = parseFloat(inputLat.value);
    const lng = parseFloat(inputLng.value);
    if (marcador) marcador.setLatLng([lat, lng]);
    else marcador = L.marker([lat, lng]).addTo(mapa);
  }

  /** Guarda la ubicación en los campos ocultos (el servidor la redondea). */
  function guardarUbicacion(lat, lng, mensaje) {
    inputLat.value = lat.toFixed(6);
    inputLng.value = lng.toFixed(6);
    estado.textContent = mensaje;
    aviso.hidden = true;
    ponerMarcador();
  }

  /** Crea el mapa la primera vez que se abre; después solo lo reajusta. */
  function iniciarMapa() {
    if (mapa) {
      mapa.invalidateSize();
      return;
    }
    const tieneUbicacion = inputLat.value && inputLng.value;
    const centro = tieneUbicacion
      ? [parseFloat(inputLat.value), parseFloat(inputLng.value)]
      : [-16.409, -71.537];

    mapa = L.map(contenedor).setView(centro, tieneUbicacion ? 14 : 12);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(mapa);

    mapa.on('click', (e) => {
      guardarUbicacion(e.latlng.lat, e.latlng.lng, 'Ubicación marcada en el mapa. Se guardará de forma aproximada.');
    });
    ponerMarcador();
  }

  // Si el formulario volvió con errores, la ubicación anterior sigue guardada.
  if (inputLat.value && inputLng.value) {
    estado.textContent = 'Ubicación ya marcada. Puedes cambiarla si quieres.';
  }

  // 1) Elegir el distrito.
  zona.addEventListener('change', () => {
    const opcion = zona.options[zona.selectedIndex];
    if (!opcion || !opcion.dataset.lat) return;
    const lat = parseFloat(opcion.dataset.lat);
    const lng = parseFloat(opcion.dataset.lng);
    guardarUbicacion(lat, lng, 'Zona elegida: ' + opcion.value + '. Se guardará de forma aproximada.');
    if (mapa) mapa.setView([lat, lng], 14);
  });

  // 2) Afinar en el mapa (el mapa se crea al abrir el recuadro).
  detalles.addEventListener('toggle', () => {
    if (detalles.open) setTimeout(iniciarMapa, 50);
  });

  // 3) Usar la ubicación actual (solo al pulsar el botón).
  document.getElementById('usar-ubicacion').addEventListener('click', () => {
    if (!navigator.geolocation) {
      estado.textContent = 'Tu navegador no permite obtener la ubicación. Elige tu zona en la lista.';
      return;
    }
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        guardarUbicacion(pos.coords.latitude, pos.coords.longitude, 'Ubicación actual guardada de forma aproximada.');
        if (mapa) mapa.setView([pos.coords.latitude, pos.coords.longitude], 15);
      },
      () => {
        estado.textContent = 'No se pudo obtener tu ubicación. Elige tu zona en la lista.';
      }
    );
  });

  /** Muestra un aviso claro y lleva la vista al campo que falta. */
  function mostrarAviso(texto, campo) {
    aviso.textContent = texto;
    aviso.hidden = false;
    campo.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  // Avisos amables antes de enviar (la validación definitiva la hace el servidor).
  formulario.addEventListener('submit', (e) => {
    if (!formulario.querySelector('input[name="categoria_id"]:checked')) {
      e.preventDefault();
      mostrarAviso('Elige qué viste (paso 1).', formulario.querySelector('.categorias'));
      return;
    }
    if (document.getElementById('descripcion').value.trim().length < 10) {
      e.preventDefault();
      mostrarAviso('Cuéntalo en pocas palabras: escribe al menos 10 caracteres (paso 2).', document.getElementById('descripcion'));
      return;
    }
    if (!inputLat.value || !inputLng.value) {
      e.preventDefault();
      mostrarAviso('Elige tu zona en la lista (paso 3).', zona);
    }
  });
});