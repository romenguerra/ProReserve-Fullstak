<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reserva Pendiente de Confirmación</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f5;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .header {
            background: linear-gradient(135deg, #2e2417 0%, #45321c 100%);
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 40px;
            color: #334155;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #1e293b;
        }
        .status-badge {
            display: inline-block;
            background-color: #fef3c7;
            border: 1px solid #fcd34d;
            color: #d97706;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 16px;
            border-radius: 9999px;
            margin-bottom: 24px;
        }
        .message {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 32px;
            color: #64748b;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #d97706;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.2s;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ProReserve</h1>
        </div>
        <div class="content">
            <div class="status-badge">⏳ RESERVA PENDIENTE DE CONFIRMACIÓN</div>
            
            <div class="greeting">¡Hola, {{ $reservation->user->name }}!</div>
            <div class="message">
                Hemos recibido tu solicitud de reserva correctamente. Tu cita se encuentra actualmente <strong>pendiente de confirmación</strong> por parte del establecimiento. 
                Te enviaremos otro correo electrónico en cuanto tu cita sea confirmada de forma definitiva. A continuación tienes los detalles solicitados:
            </div>
            
            <table class="details-table" width="100%" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; margin-bottom: 32px;">
                <tr>
                    <td style="padding: 12px 0; border-bottom: 1px solid #edf2f7;">
                        <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Establecimiento</div>
                        <div style="font-size: 16px; font-weight: 700; color: #0f172a;">{{ $reservation->reservable->name ?? 'Servicio local' }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; border-bottom: 1px solid #edf2f7;">
                        <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Servicio</div>
                        <div style="font-size: 16px; font-weight: 700; color: #0f172a;">{{ $reservation->service->name }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; border-bottom: 1px solid #edf2f7;">
                        <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Fecha</div>
                        <div style="font-size: 16px; font-weight: 700; color: #0f172a;">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; border-bottom: 1px solid #edf2f7;">
                        <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Hora</div>
                        <div style="font-size: 16px; font-weight: 700; color: #0f172a;">{{ $reservation->reservation_time }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 12px 0;">
                        <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Personas</div>
                        <div style="font-size: 16px; font-weight: 700; color: #0f172a;">{{ $reservation->guests }}</div>
                    </td>
                </tr>
            </table>

            <div class="btn-container">
                <a href="{{ url('/reservas') }}" class="btn">Ver mis solicitudes</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ProReserve. Todos los derechos reservados.<br>
            Este es un correo automático, por favor no respondas directamente.
        </div>
    </div>
</body>
</html>
