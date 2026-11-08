/**
 * Botón SOS: envía UNA sola vez la ubicación actual a un contacto de
 * confianza por WhatsApp o SMS. No hay seguimiento en tiempo real.
 * El número se guarda solo en el navegador del usuario (localStorage),
 * nunca en el servidor.
 */
document.addEventListener('DOMContentLoaded', () => {
  const inputNumero = document.getElementById('sos-numero');
  const estado = document.getElementById('sos-estado');
  if (!inputNumero) return;

  const CLAVE = 'contactoSOS';

  // localStorage puede estar bloqueado: se usa dentro de try/catch.
  try { inputNumero.value = localStorage.getItem(CLAVE) || ''; } catch (e) { /* sin almacenamiento */ }

  /** Deja solo dígitos; un número de 9 dígitos se asume peruano (+51). */
  function normalizar(valor) {
    const digitos = valor.replace(/\D/g, '');
    return digitos.length === 9 ? '51' + digitos : digitos;
  }

  document.getElementById('sos-guardar').addEventListener('click', () => {
    const numero = normalizar(inputNumero.value);
    if (numero.length < 9) { estado.textContent = 'Escribe un número válido.'; return; }
    try { localStorage.setItem(CLAVE, numero); estado.textContent = 'Contacto guardado en este teléfono.'; }
    catch (e) { estado.textContent = 'No se pudo guardar en este navegador.'; }
  });

  function enviar(canal) {
    const numero = normalizar(inputNumero.value);
    if (numero.length < 9) { estado.textContent = 'Primero escribe el número de tu contacto de confianza.'; return; }
    if (!navigator.geolocation) { estado.textContent = 'Tu navegador no permite obtener la ubicación.'; return; }

    // Consentimiento explícito antes de usar la ubicación.
    if (!window.confirm('Se enviará tu ubicación actual a tu contacto de confianza, una sola vez. ¿Continuar?')) return;

    estado.textContent = 'Obteniendo tu ubicación…';
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        const lat = pos.coords.latitude.toFixed(6);
        const lng = pos.coords.longitude.toFixed(6);
        const mapa = `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}#map=17/${lat}/${lng}`;
        const mensaje = `SOS: necesito ayuda. Mi ubicación actual: ${mapa}`;
        const url = canal === 'whatsapp'
          ? `https://wa.me/${numero}?text=${encodeURIComponent(mensaje)}`
          : `sms:+${numero}?&body=${encodeURIComponent(mensaje)}`;
        estado.textContent = 'Abriendo la aplicación para que confirmes el envío.';
        window.location.href = url;
      },
      () => { estado.textContent = 'No se pudo obtener tu ubicación. Si es una emergencia, llama directamente.'; }
    );
  }

  document.getElementById('sos-whatsapp').addEventListener('click', () => enviar('whatsapp'));
  document.getElementById('sos-sms').addEventListener('click', () => enviar('sms'));
});
