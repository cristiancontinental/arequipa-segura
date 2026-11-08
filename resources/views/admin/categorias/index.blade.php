@extends('layouts.app')

@section('titulo', 'Categorías')

@section('contenido')
    <h1>Categorías de reporte</h1>
    <a class="boton boton-verde" href="{{ route('admin.categorias.create') }}">Nueva categoría</a>

    <div class="tabla-scroll">
        <table>
            <thead><tr><th>Nombre</th><th>Estado</th><th>Reportes</th><th></th></tr></thead>
            <tbody>
                @foreach ($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->nombre }}</td>
                        <td>{{ $categoria->activa ? 'Activa' : 'Desactivada' }}</td>
                        <td>{{ $categoria->incidentes_count }}</td>
                        <td class="acciones">
                            <a class="boton boton-azul" href="{{ route('admin.categorias.edit', $categoria) }}">Editar</a>
                            <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}" onsubmit="return confirm('¿Eliminar esta categoría?');">
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
