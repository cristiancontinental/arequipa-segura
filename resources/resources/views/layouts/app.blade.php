<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Arequipa Segura')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('estilos')
</head>
<body>
    <a class="saltar" href="#contenido">Saltar al contenido</a>

    <header class="cabecera">
        <a class="marca" href="{{ route('inicio') }}">Arequipa Segura</a>
        <nav aria-label="Principal">
            <a href="{{ route('mapa') }}">Mapa</a>
            <a href="{{ route('reportes.create') }}">Reportar</a>
            <a href="{{ route('emergencias') }}">Emergencias</a>
            @auth
                <a href="{{ route('panel.reportes.index') }}">Moderación</a>
                @if (auth()->user()->esAdministrador())
                    <a href="{{ route('admin.categorias.index') }}">Categorías</a>
                    <a href="{{ route('admin.contactos.index') }}">Contactos</a>
                    <a href="{{ route('admin.usuarios.index') }}">Usuarios</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="en-linea">
                    @csrf
                    <button type="submit" class="enlace">Salir</button>
                </form>
            @endauth
        </nav>
    </header>

    <main id="contenido" class="contenedor">
        @if (session('status'))
            <div class="aviso aviso-ok" role="status">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="aviso aviso-error" role="alert">
                <strong>Revisa lo siguiente:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('contenido')
    </main>

    <footer class="pie">
        <p>Arequipa Segura es una herramienta complementaria: no reemplaza a la Policía Nacional, los Bomberos ni otras instituciones responsables de las emergencias.</p>
    </footer>

    @stack('scripts')
</body>
</html>
