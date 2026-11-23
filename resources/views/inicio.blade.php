@extends('layouts.app')

@section('titulo', 'Arequipa Segura')

@section('contenido')
    <h1>¿Qué necesitas hacer?</h1>
    <div class="opciones">
        <a id="tour-mapa" class="boton boton-grande boton-azul" href="{{ route('mapa') }}">Ver mapa preventivo</a>
        <a id="tour-reportar" class="boton boton-grande boton-naranja" href="{{ route('reportes.create') }}">Reportar una situación</a>
        <a id="tour-ayuda" class="boton boton-grande boton-rojo" href="{{ route('emergencias') }}">Necesito ayuda</a>
    </div>

    @if ($rapidos->isNotEmpty())
        <section id="tour-llamada" class="llamada-rapida" aria-labelledby="titulo-llamada-rapida">
            <h2 id="titulo-llamada-rapida">Llamada rápida</h2>
            @foreach ($rapidos as $rapido)
                <a class="boton boton-rojo" href="tel:{{ $rapido['contacto']->telefono }}">
                    {{ $etiquetas[$rapido['situacion']] }} — {{ $rapido['contacto']->telefono }}

                </a>
            @endforeach
            <p class="nota">Se abre el marcador de tu teléfono; tú confirmas la llamada.</p>
        </section>
    @endif

    <p class="nota">Puedes consultar el mapa y enviar reportes sin crear una cuenta.</p>

@endsection
