@extends('layouts.app')

@section('titulo', 'Contactos de emergencia')

@section('contenido')
    <h1>Contactos de emergencia</h1>
    <p class="nota">Verifica que los números estén vigentes antes de publicar la aplicación.</p>
    <a class="boton boton-verde" href="{{ route('admin.contactos.create') }}">Nuevo contacto</a>

    <div class="tabla-scroll">
        <table>
            <thead><tr><th>Situación</th><th>Servicio</th><th>Teléfono</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                @foreach ($contactos as $contacto)
                    <tr>
                        <td>{{ $etiquetas[$contacto->situacion] ?? $contacto->situacion }}</td>
                        <td>{{ $contacto->servicio }}</td>
                        <td>{{ $contacto->telefono }}</td>
                        <td>{{ $contacto->activo ? 'Activo' : 'Inactivo' }}</td>
                        <td class="acciones">
                            <a class="boton boton-azul" href="{{ route('admin.contactos.edit', $contacto) }}">Editar</a>
                            <form method="POST" action="{{ route('admin.contactos.destroy', $contacto) }}" onsubmit="return confirm('¿Eliminar este contacto?');">
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
