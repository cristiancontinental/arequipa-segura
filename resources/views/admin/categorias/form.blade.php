@extends('layouts.app')

@section('titulo', $categoria->exists ? 'Editar categoría' : 'Nueva categoría')

@section('contenido')
    <h1>{{ $categoria->exists ? 'Editar categoría' : 'Nueva categoría' }}</h1>

    <form method="POST" action="{{ $categoria->exists ? route('admin.categorias.update', $categoria) : route('admin.categorias.store') }}">
        @csrf
        @if ($categoria->exists) @method('PUT') @endif

        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" type="text" maxlength="60" value="{{ old('nombre', $categoria->nombre) }}" required>

        <label class="casilla"><input type="checkbox" name="activa" value="1" @checked(old('activa', $categoria->activa))> Activa (aparece en el formulario)</label>

        <button type="submit" class="boton boton-azul">Guardar</button>
        <a class="boton boton-gris" href="{{ route('admin.categorias.index') }}">Cancelar</a>
    </form>
@endsection
