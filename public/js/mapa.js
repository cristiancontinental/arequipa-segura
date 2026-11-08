
document.addEventListener('DOMContentLoaded', async () => {
  const contenedor = document.getElementById('mapa');
  const estado = document.getElementById('mapa-estado');

  if (!contenedor) return;

  // Centro aproximado de la ciudad de Arequipa.
  const mapa = L.map(contenedor).setView([-16.409, -71.537], 12);

  // Mapa de OpenStreetMap.
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(mapa);

  try {
    const respuesta = await fetch(contenedor.dataset.url, {
      headers: { Accept: 'application/json' }
    });

    if (!respuesta.ok) throw new Error('Respuesta no válida');

    const puntos = await respuesta.json();

    if (puntos.length === 0) {
      estado.textContent = 'No hay alertas vigentes por ahora.';
      return;
    }

    L.heatLayer(
      puntos.map((p) => [p.lat, p.lng, p.intensidad]),
      {
        radius: 30,
        blur: 20,
        maxZoom: 15,
      }
    ).addTo(mapa);

    estado.textContent = `Zonas con alertas recientes: ${puntos.length}.`;estado.textContent = `Zonas con alertas recientes: ${puntos.length}.`;
estado.style.color = 'red';
estado.style.fontWeight = 'bold';

  } catch (error) {
    console.error(error);
    estado.textContent = 'No se pudo cargar el mapa. Revisa la lista de alertas de abajo.';
  }
});
