@extends('layouts.app')

@section('titulo', 'Usuarios')

@section('contenido')
    <h1>Moderadores y administradores</h1>
    <a class="boton boton-verde" href="{{ route('admin.usuarios.create') }}">Nuevo usuario</a>

    <div class="tabla-scroll">
        <table>
            <thead><tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ ucfirst($usuario->rol) }}</td>
                        <td>{{ $usuario->activo ? 'Activo' : 'Desactivado' }}</td>
                        <td class="acciones">
                            <a class="boton boton-azul" href="{{ route('admin.usuarios.edit', $usuario) }}">Editar</a>
                            <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}" onsubmit="return confirm('¿Eliminar este usuario?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="boton boton-rojo">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
