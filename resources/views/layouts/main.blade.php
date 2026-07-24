<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel') · {{ env('SYSTEM_TITLE', 'RIFAS') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&family=JetBrains+Mono:wght@500&family=Geist:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-screen overflow-hidden bg-background text-on-background font-admin">

    {{-- Sidebar --}}
    <x-sidebar />

    {{-- Contenido --}}
    <main class="flex-1 flex flex-col md:ml-[280px] h-full relative">
        {{-- Header --}}
        <x-header />

        <div class="flex-1 overflow-y-auto p-gutter pb-xl space-y-xl">
            @yield('content')
        </div>
    </main>

</body>
</html>
