@extends('layouts.app')

@section('titulo', $usuario->exists ? 'Editar usuario' : 'Nuevo usuario')

@section('contenido')
    <h1>{{ $usuario->exists ? 'Editar usuario' : 'Nuevo usuario' }}</h1>

    <form method="POST" action="{{ $usuario->exists ? route('admin.usuarios.update', $usuario) : route('admin.usuarios.store') }}">
        @csrf
        @if ($usuario->exists) @method('PUT') @endif

        <label for="name">Nombre</label>
        <input id="name" name="name" type="text" maxlength="100" value="{{ old('name', $usuario->name) }}" required>

        <label for="email">Correo</label>
        <input id="email" name="email" type="email" maxlength="150" value="{{ old('email', $usuario->email) }}" required>

        <label for="rol">Rol</label>
        <select id="rol" name="rol" required>
            <option value="moderador" @selected(old('rol', $usuario->rol) === 'moderador')>Moderador</option>
            <option value="administrador" @selected(old('rol', $usuario->rol) === 'administrador')>Administrador</option>
        </select>

        <label for="password">Contraseña {{ $usuario->exists ? '(déjala vacía para no cambiarla)' : '' }}</label>
        <input id="password" name="password" type="password" minlength="8" autocomplete="new-password" {{ $usuario->exists ? '' : 'required' }}>

        <label class="casilla"><input type="checkbox" name="activo" value="1" @checked(old('activo', $usuario->activo))> Cuenta activa</label>

        <button type="submit" class="boton boton-azul">Guardar</button>
        <a class="boton boton-gris" href="{{ route('admin.usuarios.index') }}">Cancelar</a>
    </form>
@endsection
