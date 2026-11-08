@extends('layouts.app')

@section('titulo', 'Ingresar')

@section('contenido')
    <h1>Ingreso de moderadores</h1>
    <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <label for="email">Correo</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">

        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">

        <button type="submit" class="boton boton-azul">Ingresar</button>
    </form>
@endsection
