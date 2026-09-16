<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 35px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            border: 1px solid #222;
            padding: 8px;
            font-size: 13px;
        }

        th {
            background: #e5e7eb;
        }

        .boton {
            padding: 10px 14px;
            background: #111827;
            color: white;
            border: none;
            cursor: pointer;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<button class="boton no-print" onclick="window.print()">Guardar como PDF / Imprimir</button>

<h1>Reporte de Movilidades</h1>

<p><b>Fecha:</b> {{ date('d/m/Y') }}</p>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Año</th>
            <th>Placa</th>
            <th>Color</th>
            <th>Precio</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vehiculos as $v)
            <tr>
                <td>{{ $v->id }}</td>
                <td>{{ $v->marca }}</td>
                <td>{{ $v->modelo }}</td>
                <td>{{ $v->anio }}</td>
                <td>{{ $v->placa }}</td>
                <td>{{ $v->color }}</td>
                <td>Bs {{ number_format($v->precio, 2) }}</td>
                <td>{{ $v->estado }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>