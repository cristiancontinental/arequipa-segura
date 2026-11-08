@extends('layouts.app')

@section('titulo', $contacto->exists ? 'Editar contacto' : 'Nuevo contacto')

@section('contenido')
    <h1>{{ $contacto->exists ? 'Editar contacto' : 'Nuevo contacto' }}</h1>

    <form method="POST" action="{{ $contacto->exists ? route('admin.contactos.update', $contacto) : route('admin.contactos.store') }}">
        @csrf
        @if ($contacto->exists) @method('PUT') @endif

        <label for="situacion">Situación</label>
        <select id="situacion" name="situacion" required>
            @foreach ($situaciones as $clave => $etiqueta)
                <option value="{{ $clave }}" @selected(old('situacion', $contacto->situacion) === $clave)>{{ $etiqueta }}</option>
            @endforeach
        </select>

        <label for="servicio">Servicio</label>
        <input id="servicio" name="servicio" type="text" maxlength="80" value="{{ old('servicio', $contacto->servicio) }}" required>

        <label for="telefono">Teléfono</label>
        <input id="telefono" name="telefono" type="tel" maxlength="16" value="{{ old('telefono', $contacto->telefono) }}" required>

        <label for="instruccion">Instrucción breve (opcional)</label>
        <input id="instruccion" name="instruccion" type="text" maxlength="255" value="{{ old('instruccion', $contacto->instruccion) }}">

        <label class="casilla"><input type="checkbox" name="activo" value="1" @checked(old('activo', $contacto->activo))> Activo (se muestra en la aplicación)</label>

        <button type="submit" class="boton boton-azul">Guardar</button>
        <a class="boton boton-gris" href="{{ route('admin.contactos.index') }}">Cancelar</a>
    </form>
@endsection
