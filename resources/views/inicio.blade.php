@extends('layouts.app')

@section('titulo', 'Arequipa Segura')

@section('contenido')
    <h1>¿Qué necesitas hacer?</h1>
    <div class="opciones">
        <a class="boton boton-grande boton-azul" href="{{ route('mapa') }}">Ver mapa preventivo</a>
        <a class="boton boton-grande boton-naranja" href="{{ route('reportes.create') }}">Reportar una situación</a>
        <a class="boton boton-grande boton-rojo" href="{{ route('emergencias') }}">Necesito ayuda</a>
    </div>
    <p class="nota">Puedes consultar el mapa y enviar reportes sin crear una cuenta.</p>
@endsection
