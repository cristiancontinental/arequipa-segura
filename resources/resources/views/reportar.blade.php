@extends('layouts.app')

@section('titulo', 'Reportar una situación')

@push('estilos')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css">
@endpush

@section('contenido')
    <h1>Reportar una situación</h1>
    <p class="nota">No necesitas cuenta: no se pide nombre, correo ni teléfono. Tu reporte será revisado por un moderador antes de publicarse. No escribas nombres de personas ni datos personales.</p>

    <form id="form-reporte" method="POST" action="{{ route('reportes.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        <label for="categoria_id">Categoría</label>
        <select id="categoria_id" name="categoria_id" required>
            <option value="">Elige una opción</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>{{ $categoria->nombre }}</option>
            @endforeach
        </select>

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="4" minlength="10" maxlength="500" required>{{ old('descripcion') }}</textarea>

        <fieldset>
            <legend>Ubicación aproximada</legend>
            <p class="nota">Toca el mapa para marcar la zona. Se guardará de forma aproximada (unos 100 metros).</p>
            <div id="mapa-reporte" aria-label="Mapa para marcar la ubicación aproximada"></div>
            <button type="button" id="usar-ubicacion" class="boton boton-gris">Usar mi ubicación actual (opcional)</button>
            <p id="ubicacion-estado" class="nota" aria-live="polite"></p>
            <input type="hidden" id="latitud" name="latitud" value="{{ old('latitud') }}">
            <input type="hidden" id="longitud" name="longitud" value="{{ old('longitud') }}">
        </fieldset>

        <label for="evidencia">Fotografía (opcional, JPG o PNG de hasta 2 MB)</label>
        <input id="evidencia" name="evidencia" type="file" accept="image/jpeg,image/png">
        <p class="nota">La fotografía no se publica: solo la ve un moderador. Evita rostros y placas.</p>

        <button type="submit" class="boton boton-grande boton-verde">Enviar para revisión</button>
    </form>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <script src="{{ asset('js/reportar.js') }}"></script>
@endpush
