<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe de Establecimientos - ProReserve</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #8EB6A5; padding-bottom: 10px; }
        .header h1 { color: #0f172a; margin: 0; }
        .header p { color: #666; font-size: 11px; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        th { background-color: #f8fafc; color: #0f172a; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .status-active { color: #16a34a; font-weight: bold; }
        .status-pending { color: #d97706; font-weight: bold; }
        .status-rejected { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ProReserve - Informe de Establecimientos</h1>
        <p>Documento generado automáticamente el {{ date('d/m/Y') }} a las {{ date('H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Ciudad</th>
                <th>Dirección</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($locals as $local)
            <tr>
                <td>{{ $local->id }}</td>
                <td>{{ $local->name }}</td>
                <td>{{ class_basename($local) }}</td>
                <td>{{ $local->city }}</td>
                <td>{{ $local->address }}</td>
                <td class="status-{{ $local->status }}">{{ strtoupper($local->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
