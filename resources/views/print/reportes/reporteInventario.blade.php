<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @page {
            size: 279.4mm 216mm
        }

        table {
            border-collapse: collapse;
            table-layout: fixed;
        }

        table td th {
            word-wrap: break-word;
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
        <th>Costo Un/Kg</th>
        <th>Utilidad Un/kg</th>
        <th>Precio Un/kg</th>
        <th>Un/g en stock</th>
        <th>Tipo de producto</th>
        <th>F/ creación</th>
        <th>F/ actualización</th>
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
            <td class="service">{{ $registro->tipo->nombre }}</td>
            <td class="service">{{ $registro->created_at }}</td>
            <td class="service">{{ $registro->updated_at }}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold">
        <td class="grand total" colspan="3">Totales</td>
        <td class="grand total" colspan="2">Total costo de inventario: {{$costoTotal}}</td>
        <td class="grand total" colspan="2">Utilidades totales en inventario: {{$utilidadTotal}}</td>
        <td class="grand total" colspan="2">Total precio del inventario: {{$precioTotal}}</td>
        <td class="grand total"></td>
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
