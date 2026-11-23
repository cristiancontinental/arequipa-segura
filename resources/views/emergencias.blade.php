@extends('layouts.app')

@section('titulo', 'Emergencias')

@section('contenido')
    <h1>Necesito ayuda</h1>
    <p class="nota">Estos botones abren el marcador de tu teléfono. La aplicación <strong>no</strong> contacta automáticamente a las autoridades y estos casos no se publican en el mapa.</p>

    <div class="emergencias-grid">
        @forelse ($grupos as $situacion => $contactos)
            <section class="grupo-emergencia tarjeta-emergencia">
                <h2>{{ $etiquetas[$situacion] }}</h2>

                @foreach ($contactos as $contacto)
                    <p class="telefono-emergencia">
                        {{ $contacto->servicio }}: {{ $contacto->telefono }}
                    </p>

                    <a class="boton boton-grande boton-rojo"
                       href="tel:{{ $contacto->telefono }}">
                        Llamar ahora
                    </a>

                    @if ($contacto->instruccion)
                        <p class="nota">{{ $contacto->instruccion }}</p>
                    @endif
                @endforeach
            </section>
        @empty
            <p>Todavía no hay contactos configurados.</p>
        @endforelse
    </div>

    <section class="grupo-emergencia" id="sos">
        <h2>Botón SOS</h2>

        <p class="nota">Envía <strong>una sola vez</strong> tu ubicación actual a un contacto de confianza. Tú decides cuándo. No es un seguimiento en tiempo real, y el número se guarda solo en este teléfono, no en nuestros servidores.</p>

        <label for="sos-numero">Número de tu contacto de confianza</label>
        <input id="sos-numero" type="tel" inputmode="tel" placeholder="Ej.: 987654321" autocomplete="off">

        <button type="button" id="sos-guardar" class="boton boton-gris">
            Guardar contacto en este teléfono
        </button>

        <div class="opciones">
            <button type="button" id="sos-whatsapp" class="boton boton-grande boton-negro">SOS por WhatsApp</button>
            <button type="button" id="sos-sms" class="boton boton-grande boton-negro">SOS por SMS</button>
        </div>

        <p id="sos-estado" class="nota" aria-live="polite"></p>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/sos.js') }}"></script>
@endpush