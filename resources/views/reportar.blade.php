@extends('layouts.app')

@section('titulo', 'Reportar una situación')

@push('estilos')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css">
@endpush

@php
    // Centros aproximados de los distritos. Se guardan redondeados (unos 110 metros).
    $zonas = [
        'Arequipa (Cercado)' => [-16.399, -71.535],
        'Alto Selva Alegre' => [-16.375, -71.520],
        'Cayma' => [-16.370, -71.540],
        'Cerro Colorado' => [-16.380, -71.560],
        'Jacobo Hunter' => [-16.440, -71.560],
        'José Luis Bustamante y Rivero' => [-16.420, -71.520],
        'Mariano Melgar' => [-16.400, -71.500],
        'Miraflores' => [-16.395, -71.520],
        'Paucarpata' => [-16.430, -71.500],
        'Sabandía' => [-16.455, -71.495],
        'Sachaca' => [-16.420, -71.570],
        'Yanahuara' => [-16.390, -71.545],
    ];

@endphp

@section('contenido')
    <h1>Reportar una situación</h1>
    <p class="nota">No necesitas cuenta: no se pide nombre, correo ni teléfono. Tu reporte será revisado por un moderador antes de publicarse. No escribas nombres de personas ni datos personales.</p>

    <form id="form-reporte" method="POST" action="{{ route('reportes.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        <fieldset class="paso">
            <legend>1. ¿Qué viste?</legend>
            <div class="categorias">
                @foreach ($categorias as $categoria)
                    <label class="categoria-opcion">
                        <input type="radio" name="categoria_id" value="{{ $categoria->id }}" @checked(old('categoria_id') == $categoria->id) required>
                        <span>{{ $categoria->nombre }}</span>
                    </label>
                @endforeach
            </div>
        </fieldset>

        <fieldset class="paso">
            <legend>2. Cuéntalo en pocas palabras</legend>
            <label for="descripcion" class="solo-lectores">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="3" minlength="10" maxlength="500" required placeholder="Ej.: Un auto sin placa ofreció llevarme y cambió de ruta.">{{ old('descripcion') }}</textarea>
        </fieldset>

        <fieldset class="paso">
            <legend>3. ¿En qué zona?</legend>
            <label for="zona">Elige el distrito</label>
            <select id="zona">
                <option value="">Elige una zona</option>
                @foreach ($zonas as $nombre => [$lat, $lng])
                    <option value="{{ $nombre }}" data-lat="{{ $lat }}" data-lng="{{ $lng }}">{{ $nombre }}</option>
                @endforeach
            </select>

            <details class="afinar" id="afinar">
                <summary>Afinar en el mapa (opcional)</summary>
                <p class="nota">Toca el mapa para mover el punto. Se guardará de forma aproximada (unos 100 metros).</p>
                <div id="mapa-reporte" aria-label="Mapa para marcar la ubicación aproximada"></div>
                <button type="button" id="usar-ubicacion" class="boton boton-gris">Usar mi ubicación actual (opcional)</button>
            </details>

            <p id="ubicacion-estado" class="nota" aria-live="polite"></p>
            <input type="hidden" id="latitud" name="latitud" value="{{ old('latitud') }}">
            <input type="hidden" id="longitud" name="longitud" value="{{ old('longitud') }}">
        </fieldset>

        <details class="afinar">
            <summary>Agregar una fotografía (opcional)</summary>
            <label for="evidencia">JPG o PNG de hasta 2 MB</label>
            <input id="evidencia" name="evidencia" type="file" accept="image/jpeg,image/png">
            <p class="nota">La fotografía no se publica: solo la ve un moderador. Evita rostros y placas.</p>
        </details>

        <p id="aviso-formulario" class="aviso aviso-error" role="alert" hidden></p>

        <button type="submit" class="boton boton-grande boton-verde">Enviar para revisión</button>
    </form>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <script src="{{ asset('js/reportar.js') }}"></script>
@endpush