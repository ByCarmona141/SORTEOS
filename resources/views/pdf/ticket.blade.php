<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 26px;
            font-family: sans-serif;
            background-color: #f4ede1;
        }

        /* El boleto completo es UNA sola pieza con marco doble,
           como una tarjeta de lotería clásica. Todo vive adentro
           de este mismo contenedor: nada queda "flotando" separado. */
        .ticket {
            border: 3px solid #613b00;
            padding: 4px;
            background: #fffaf0;
        }

        .ticket-inner {
            border: 1px solid #a08e7a;
            padding: 22px 26px;
        }

        /* Encabezado: nombre del sistema */
        .brand {
            text-align: center;
            font-size: 10px;
            letter-spacing: 3px;
            color: #a08e7a;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .raffle-name {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #613b00;
            margin-bottom: 18px;
        }

        /* Franja del número, dentro del mismo boleto, no es una tarjeta aparte */
        .number-band {
            background: linear-gradient(90deg, #f59e0b, #ffc174, #f59e0b);
            border-radius: 6px;
            padding: 14px 0;
            text-align: center;
        }

        .number-label {
            font-size: 10px;
            letter-spacing: 4px;
            color: #613b00;
            text-transform: uppercase;
        }

        .number-value {
            font-size: 44px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #613b00;
            margin-top: 2px;
        }

        /* Línea de "perforado", simulando el talón de un boleto físico */
        .perforation {
            position: relative;
            border-top: 2px dashed #a08e7a;
            margin: 22px 0;
        }

        .perforation:before,
        .perforation:after {
            content: "";
            position: absolute;
            top: -8px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #f4ede1;
            border: 1px solid #a08e7a;
        }

        .perforation:before { left: -32px; }
        .perforation:after { right: -32px; }

        /* Datos del boleto, dentro del mismo marco */
        .details {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }

        .details td {
            padding: 6px 4px;
        }

        .details td.label {
            color: #706f6c;
            font-weight: bold;
            width: 42%;
        }

        .details td.value {
            color: #1d2021;
            text-align: right;
        }

        /* Pie: QR + leyenda, todo dentro del boleto también */
        .footer-row {
            margin-top: 18px;
            text-align: center;
        }

        .footer-row img {
            width: 90px;
            height: 90px;
        }

        .footer-note {
            font-size: 9px;
            color: #a08e7a;
            margin-top: 6px;
        }
    </style>
</head>
<body>

    <div class="ticket">
        <div class="ticket-inner">

            <p class="brand">{{ config('app.name', 'Rifas de la Montaña') }}</p>
            <p class="raffle-name">{{ $raffle->name }}</p>

            <div class="number-band">
                <p class="number-label">Boleto</p>
                <p class="number-value">{{ $ticket->number }}</p>
            </div>

            <div class="perforation"></div>

            <table class="details">
                <tr>
                    <td class="label">Participante</td>
                    <td class="value">{{ $user->name ?? 'N/D' }}</td>
                </tr>
                <tr>
                    <td class="label">Precio del boleto</td>
                    <td class="value">${{ number_format($raffle->ticket_price, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Fecha de pago</td>
                    <td class="value">{{ $ticket->paid_at?->format('d/m/Y H:i') ?? 'N/D' }}</td>
                </tr>
                @if ($raffle->draw_date)
                <tr>
                    <td class="label">Fecha del sorteo</td>
                    <td class="value">{{ $raffle->draw_date->format('d/m/Y H:i') }}</td>
                </tr>
                @endif
            </table>

            <div class="perforation"></div>

            <div class="footer-row">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}">
                <p class="footer-note">Escanea para verificar la autenticidad de este boleto</p>
            </div>

        </div>
    </div>

</body>
</html>