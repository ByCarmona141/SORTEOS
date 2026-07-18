<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Sorteos')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    {{-- Esto reemplaza el <script src="cdn.tailwindcss.com">.
         Vite compila el CSS real y lo inyecta aquí. --}}
    @vite(['resources/css/login.css', 'resources/js/app.js'])

    @stack('css') {{-- por si alguna página necesita algo extra --}}
</head>
<body class="min-h-screen bg-casino-darker font-body">

    {{-- Fondo compartido en TODAS las páginas que usen este layout --}}
    <div class="fixed inset-0 bg-gradient-to-br from-casino-dark via-casino-darker to-black -z-20"></div>
    <div class="casino-glow fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-casino-gold rounded-full blur-[120px] pointer-events-none -z-10"></div>
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] bg-casino-red/30 rounded-full blur-[100px] pointer-events-none -z-10"></div>

    {{-- Aquí cada página mete su propio contenido --}}
    <main class="relative min-h-screen">
        @yield('content')
    </main>

    @stack('js')
</body>
</html>
