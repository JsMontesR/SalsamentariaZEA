<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @page {
            size: 279.4mm 216mm
        }

        table {
            border: #b2b2b2 1px solid;
        }

        td {
            border: black 1px solid;
        }

        th {
            border: black 1px solid;
        }
    </style>
    <meta charset="utf-8">
    <title>Example 1</title>
    <link rel="stylesheet" href="{{asset('vendor/invoices/style.css')}}" media="all"/>
</head>
<body>
<header class="clearfix">
    <div id="logo">
        <img src="{{asset('favicon.png')}}">
    </div>
    <h1>Reporte de inventario</h1>
</header>
<h3>Detalle del reporte:</h3>
<table>
    <thead>
    <tr>
        <th>Id</th>
        <th class="service">Nombre</th>
        <th class="service">Categoría</th>
        <th>Costo Unitario/Kg</th>
        <th>Utilidad unitaria/kg</th>
        <th>Precio unitario/kg</th>
        <th>Unidades/g en stock</th>
        <th>Tipo de producto</th>
        <th>Fecha de creación</th>
        <th>Fecha de actualización</th>
    </tr>
    </thead>
    <tbody>
    @foreach($registros as $registro)
        <tr>
            <td class="id">{{ $registro->id }}</td>
            <td class="service">{{ $registro->nombre }}</td>
            <td class="service">{{ $registro->categoria }}</td>
            <td class="unit">{{ "$ ". number_format($registro->costo,0) }}</td>
            <td class="qty">{{ "% ". number_format($registro->utilidad,0) }}</td>
            <td class="qty">{{ "$ ". number_format($registro->precio,0) }}</td>
            <td class="service">{{ $registro->stock }}</td>
            <td class="service">{{ $registro->tipo_producto->nombre }}</td>
            <td class="service">{{ $registro->created_at }}</td>
            <td class="service">{{ $registro->updated_at }}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold">
        <td class="grand total" colspan="3">Totales</td>
        <td class="grand total">Total vendido: {{$totalVendido}}</td>
        <td class="grand total">Total abonado: {{$totalAbonado}}</td>
        <td class="grand total">Total saldo: {{$totalSaldo}}</td>
        <td class="grand total">Total costo: {{$totalCosto}}</td>
        <td class="grand total">Total utilidades: {{$totalUtilidades}}</td>
        <td class="grand total">Utilidades devengadas: {{$utilidadesDevengadas}}</td>
    </tr>
    </tbody>
</table>
<br>
<div id="notices">
    <div align="center" class="notice">Salsamentaria ZEA</div>
    <div align="center" class="notice">
        Fecha y hora de impresión: {{ $fechaActual }}
    </div>
</div>

</body>
</html>
