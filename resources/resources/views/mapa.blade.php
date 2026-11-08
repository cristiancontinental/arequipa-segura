
@extends('layouts.app')

@section('titulo', 'Mapa preventivo')

@push('estilos')
    {{-- Leaflet 1.9.4 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css">
@endpush

@section('contenido')
    <h1>Mapa preventivo</h1>
    <p class="nota">Alertas ciudadanas de los últimos 7 días. Es información <strong>no verificada</strong>: no son hechos confirmados ni deben usarse para señalar a personas.</p>

    <div id="mapa"
        data-url="{{ route('api.alertas') }}"
        role="img"
        aria-label="Mapa de calor con las zonas que tienen alertas recientes">
    </div>

    <p id="mapa-estado" class="nota" aria-live="polite"></p>

    <h2>Lista de alertas vigentes</h2>
    @forelse ($alertas as $alerta)
        <article class="tarjeta">
            <h3>{{ $alerta->categoria->nombre }}</h3>
            <p class="meta">Publicada {{ $alerta->aprobado_en?->diffForHumans() }} · vence {{ $alerta->vence_en?->diffForHumans() }}</p>
            <p>{{ $alerta->descripcion }}</p>
            <details>
                <summary>Marcar como inapropiada</summary>
                <form method="POST" action="{{ route('alertas.inapropiado', $alerta) }}">
                    @csrf
                    <label for="motivo-{{ $alerta->id }}">Motivo</label>
                    <input id="motivo-{{ $alerta->id }}" name="motivo" type="text" minlength="5" maxlength="255" required>
                    <button type="submit" class="boton boton-gris">Enviar aviso</button>
                </form>
            </details>
        </article>
    @empty
        <p>No hay alertas vigentes por ahora.</p>
    @endforelse
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.heat/0.2.0/leaflet-heat.js"></script>
    <script src="{{ asset('js/mapa.js') }}"></script>
@endpush
