/**
 * Formulario de reporte: permite marcar la ubicación aproximada tocando el
 * mapa. "Usar mi ubicación" es opcional y solo se activa al pulsar el botón.
 * Librería: Leaflet 1.9.4 (BSD-2-Clause). Mapa base: © OpenStreetMap contributors.
 */
document.addEventListener('DOMContentLoaded', () => {
  const contenedor = document.getElementById('mapa-reporte');
  const inputLat = document.getElementById('latitud');
  const inputLng = document.getElementById('longitud');
  const estado = document.getElementById('ubicacion-estado');
  const formulario = document.getElementById('form-reporte');
  if (!contenedor) return;

  const mapa = L.map(contenedor).setView([-16.409, -71.537], 12);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(mapa);

  let marcador = null;

  function marcar(lat, lng) {
    inputLat.value = lat.toFixed(6);
    inputLng.value = lng.toFixed(6);
    if (marcador) marcador.setLatLng([lat, lng]);
    else marcador = L.marker([lat, lng]).addTo(mapa);
    estado.textContent = 'Ubicación marcada. Se guardará de forma aproximada.';
  }

  // Si el formulario volvió con errores, se restaura el marcador anterior.
  if (inputLat.value && inputLng.value) {
    marcar(parseFloat(inputLat.value), parseFloat(inputLng.value));
  }

  mapa.on('click', (e) => marcar(e.latlng.lat, e.latlng.lng));

  document.getElementById('usar-ubicacion').addEventListener('click', () => {
    if (!navigator.geolocation) {
      estado.textContent = 'Tu navegador no permite obtener la ubicación. Marca la zona en el mapa.';
      return;
    }
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        marcar(pos.coords.latitude, pos.coords.longitude);
        mapa.setView([pos.coords.latitude, pos.coords.longitude], 15);
      },
      () => { estado.textContent = 'No se pudo obtener tu ubicación. Marca la zona en el mapa.'; }
    );
  });

  // Aviso amable si falta la ubicación (la validación definitiva la hace el servidor).
  formulario.addEventListener('submit', (e) => {
    if (!inputLat.value || !inputLng.value) {
      e.preventDefault();
      estado.textContent = 'Marca la ubicación aproximada en el mapa antes de enviar.';
      contenedor.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });
});
