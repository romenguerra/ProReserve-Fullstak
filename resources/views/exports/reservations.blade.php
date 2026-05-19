<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe de Reservas - ProReserve</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #8EB6A5; padding-bottom: 10px; }
        .header h1 { color: #0f172a; margin: 0; }
        .header p { color: #666; font-size: 11px; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        th { background-color: #f8fafc; color: #0f172a; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .status-confirmed, .status-confirmada { color: #16a34a; font-weight: bold; }
        .status-pending, .status-pendiente { color: #d97706; font-weight: bold; }
        .status-cancelled, .status-cancelada { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ProReserve - Informe de Reservas</h1>
        <p>Documento generado automáticamente el {{ date('d/m/Y') }} a las {{ date('H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Local</th>
                <th>Servicio</th>
                <th>Fecha y Hora</th>
                <th>Personas</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reserva)
            <tr>
                <td>{{ $reserva->id }}</td>
                <td>{{ $reserva->user->name ?? $reserva->customer_name }}</td>
                <td>{{ $reserva->reservable->name ?? 'N/A' }}</td>
                <td>{{ $reserva->service->name ?? 'N/A' }}</td>
                <td>{{ $reserva->reservation_date }} {{ substr($reserva->reservation_time, 0, 5) }}h</td>
                <td>{{ $reserva->guests ?? 1 }}</td>
                <td class="status-{{ strtolower($reserva->status) }}">{{ strtoupper($reserva->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
