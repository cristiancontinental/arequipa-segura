@extends('layouts.app')

@section('titulo', 'Revisar reporte')

@section('contenido')
    <h1>Reporte #{{ $incidente->id }} <span class="etiqueta">{{ ucfirst($incidente->estado) }}</span></h1>
    <p class="meta">Ubicación aproximada: {{ $incidente->latitud_aprox }}, {{ $incidente->longitud_aprox }}
        · Recibido {{ $incidente->created_at->format('d/m/Y H:i') }}</p>

    <form method="POST" action="{{ route('panel.reportes.update', $incidente) }}">
        @csrf
        @method('PUT')

        <label for="categoria_id">Categoría</label>
        <select id="categoria_id" name="categoria_id" required>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('categoria_id', $incidente->categoria_id) == $categoria->id)>{{ $categoria->nombre }}</option>
            @endforeach
        </select>

        <label for="descripcion">Descripción (corrige datos personales si los hay)</label>
        <textarea id="descripcion" name="descripcion" rows="5" minlength="10" maxlength="500" required>{{ old('descripcion', $incidente->descripcion) }}</textarea>

        <button type="submit" class="boton boton-azul">Guardar cambios</button>
    </form>

    @if ($incidente->evidencias->isNotEmpty())
        <h2>Evidencias (privadas)</h2>
        <ul>
            @foreach ($incidente->evidencias as $evidencia)
                <li><a href="{{ route('panel.evidencias.show', $evidencia) }}" target="_blank" rel="noopener">Ver archivo {{ strtoupper($evidencia->tipo_archivo) }} ({{ round($evidencia->tamano / 1024) }} KB)</a></li>
            @endforeach
        </ul>
    @endif

    <h2>Decisión</h2>
    <div class="acciones">
        <form method="POST" action="{{ route('panel.reportes.aprobar', $incidente) }}">
            @csrf
            <button type="submit" class="boton boton-verde">Aprobar y publicar</button>
        </form>
        <form method="POST" action="{{ route('panel.reportes.rechazar', $incidente) }}">
            @csrf
            <label for="motivo">Motivo del rechazo (opcional)</label>
            <input id="motivo" name="motivo" type="text" maxlength="255">
            <button type="submit" class="boton boton-naranja">Rechazar</button>
        </form>
        <form method="POST" action="{{ route('panel.reportes.destroy', $incidente) }}" onsubmit="return confirm('¿Eliminar este reporte definitivamente?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="boton boton-rojo">Eliminar</button>
        </form>
    </div>

    <h2>Historial de moderación</h2>
    <ul>
        @forelse ($incidente->registros as $registro)
            <li>{{ $registro->created_at->format('d/m/Y H:i') }}: {{ $registro->accion }}
                por {{ $registro->moderador?->name ?? 'usuario eliminado' }}
                @if ($registro->motivo) ({{ $registro->motivo }}) @endif</li>
        @empty
            <li>Sin acciones registradas.</li>
        @endforelse
    </ul>
@endsection
