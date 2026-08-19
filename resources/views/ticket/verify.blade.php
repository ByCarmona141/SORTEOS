<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Boleto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-container-lowest min-h-screen flex items-center justify-center p-md font-admin text-on-surface">

    <div class="w-full max-w-[420px]">

        <div class="text-center mb-lg">
            <span class="material-symbols-outlined text-primary text-5xl">verified</span>
            <h1 class="text-headline-md text-on-surface mt-sm">Boleto verificado</h1>
            <p class="text-body-md text-on-surface-variant">Este boleto es auténtico y pertenece a nuestro sistema.</p>
        </div>

        <div class="bg-surface-container border border-surface-variant rounded-xl p-lg space-y-md">

            <div class="text-center pb-md border-b border-outline-variant/50">
                <p class="font-mono-label text-label-caps text-on-surface-variant">NÚMERO DE BOLETO</p>
                <p class="text-stats-number text-primary tracking-widest">{{ $ticket->number }}</p>
            </div>

            <div class="flex justify-between border-b border-outline-variant/50 pb-sm">
                <span class="text-on-surface-variant">Sorteo</span>
                <span class="font-bold text-on-surface text-right">{{ $ticket->raffle->name ?? 'N/D' }}</span>
            </div>

            <div class="flex justify-between border-b border-outline-variant/50 pb-sm">
                <span class="text-on-surface-variant">Estado</span>
                <span class="font-bold text-on-surface">{{ $ticket->statusTicket->name ?? 'N/D' }}</span>
            </div>

            @if ($ticket->paid_at)
                <div class="flex justify-between {{ $prizeWon ? 'border-b border-outline-variant/50 pb-sm' : '' }}">
                    <span class="text-on-surface-variant">Fecha de pago</span>
                    <span class="font-bold text-on-surface">{{ $ticket->paid_at->format('d/m/Y') }}</span>
                </div>
            @endif

            @if ($prizeWon)
                <div class="text-center bg-primary/10 border border-primary/30 rounded-lg p-md">
                    <span class="material-symbols-outlined text-primary text-3xl">emoji_events</span>
                    <p class="text-primary font-bold mt-1">¡Este boleto ganó un premio!</p>
                    <p class="text-on-surface-variant text-sm">{{ $prizeWon->title }}</p>
                </div>
            @endif

        </div>

        <p class="text-center text-label-caps text-on-surface-variant/60 mt-lg">
            {{ config('app.name', 'Rifas de la Montaña') }}
        </p>
    </div>

</body>
</html>