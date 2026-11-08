
@extends('layouts.app')

@section('titulo', 'Emergencias')

@section('contenido')
    <h1>Necesito ayuda</h1>
    <p class="nota">Estos botones abren el marcador de tu teléfono. La aplicación <strong>no</strong> contacta automáticamente a las autoridades y estos casos no se publican en el mapa.</p>

    @forelse ($grupos as $situacion => $contactos)
        <section class="grupo-emergencia">
            <h2>{{ $etiquetas[$situacion] }}</h2>

            <div class="opciones-emergencia">
                @foreach ($contactos as $contacto)
                    <article class="tarjeta-emergencia">
                        <div class="icono-emergencia" aria-hidden="true">☎</div>

                        <h3>{{ $contacto->servicio }}</h3>

                        <p class="telefono-emergencia">
                            {{ $contacto->telefono }}
                        </p>

                        <a class="boton boton-grande boton-rojo"
                           href="tel:{{ $contacto->telefono }}">
                            Llamar ahora
                        </a>

                        @if ($contacto->instruccion)
                            <p class="nota">{{ $contacto->instruccion }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
    @empty
        <p>Todavía no hay contactos configurados.</p>
    @endforelse

    <section class="grupo-emergencia tarjeta-sos" id="sos">
        <h2>Botón SOS</h2>

        <p class="nota">Envía <strong>una sola vez</strong> tu ubicación actual a un contacto de confianza. Tú decides cuándo. No es un seguimiento en tiempo real, y el número se guarda solo en este teléfono, no en nuestros servidores.</p>

        <label for="sos-numero">Número de tu contacto de confianza</label>
        <input id="sos-numero" type="tel" inputmode="tel" placeholder="Ej.: 987654321" autocomplete="off">

        <button type="button" id="sos-guardar" class="boton boton-gris">
            Guardar contacto en este teléfono
        </button>

        <div class="opciones-emergencia">
            <article class="tarjeta-emergencia">
                <h3>SOS por WhatsApp</h3>
                <button type="button" id="sos-whatsapp" class="boton boton-grande boton-negro">
                    Enviar por WhatsApp
                </button>
            </article>

            <article class="tarjeta-emergencia">
                <h3>SOS por SMS</h3>
                <button type="button" id="sos-sms" class="boton boton-grande boton-negro">
                    Enviar por SMS
                </button>
            </article>
        </div>

        <p id="sos-estado" class="nota" aria-live="polite"></p>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/sos.js') }}"></script>
@endpush
