@extends('layouts.app')

@section('titulo', 'Moderación de reportes')

@section('contenido')
    <h1>Moderación de reportes</h1>

    <nav class="filtros" aria-label="Filtrar por estado">
        @foreach (array_merge($estados, ['todos']) as $opcion)
            <a href="{{ route('panel.reportes.index', ['estado' => $opcion]) }}"
               @class(['activo' => $estado === $opcion])>{{ ucfirst($opcion) }}</a>
        @endforeach
    </nav>

    <div class="tabla-scroll">
        <table>
            <thead>
                <tr><th>N.º</th><th>Categoría</th><th>Descripción</th><th>Estado</th><th>Recibido</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($reportes as $reporte)
                    <tr>
                        <td>{{ $reporte->id }}</td>
                        <td>{{ $reporte->categoria->nombre }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($reporte->descripcion, 90) }}</td>
                        <td>{{ ucfirst($reporte->estado) }}</td>
                        <td>{{ $reporte->created_at->format('d/m/Y H:i') }}</td>
                        <td><a class="boton boton-azul" href="{{ route('panel.reportes.edit', $reporte) }}">Revisar</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6">No hay reportes en este estado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $reportes->links('pagination::default') }}
@endsection
