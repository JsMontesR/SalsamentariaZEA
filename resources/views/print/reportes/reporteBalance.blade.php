<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @page {
            size: 216mm 279mm
        }

        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;

        }

        hr {
            background-color: #403d3d;
            border: 0 none;
            color: #eee;
            height: 1px;
        }

        .negrita {
            font-weight: bold;
        }
    </style>
    <meta charset="utf-8">
    <title>Example 1</title>
    <link rel="stylesheet" href="{{asset('vendor/invoices/style.css')}}" media="all"/>
</head>
<body>

<div id="logo">
    <img src="{{asset('favicon.png')}}">
</div>
<h1>Balance</h1>
<div>
    @if($fechaInicio != null)
        <div><strong class="bold">Desde: </strong>{{$fechaInicio}}</div>
    @else
        <div><strong class="bold">Desde: </strong>el inicio de los tiempos</div>
    @endif
    @if($fechaFin != null)
        <div><strong class="bold">Hasta: </strong>{{ $fechaFin }}</div>
    @else
        <div><strong class="bold">Hasta: </strong>el día de hoy</div>
    @endif
</div>
<hr>
<h3>Detalle del reporte:</h3>
<table>
    <thead>
    <tr>
        <th>Id</th>
        <th>Concepto</th>
        <th>Naturaleza</th>
        <th>Total aporte</th>
        <th>Fecha de realización</th>
    </tr>
    </thead>
    <tbody>
    @foreach($registros as $registro)
        <tr>
            <td>{{ $registro->id }}</td>
            <td>{{ $registro->concepto }}</td>
            <td>{{ $registro->naturaleza }}</td>
            <td>{{ "$ ". number_format($registro->total,0) }}</td>
            <td>{{ $registro->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<hr>
<div>
    <label>Total ingresos ordinarios: <strong>{{ "$ ". number_format($ingresosOrdinarios,0) }}</strong></label>
    <br>
    <label>Total egresos ordinarios: <strong> {{ "$ ". number_format($egresosOrdinarios,0) }}</strong></label>
    <br>
    <label>Total ingresos extraordinarios: <strong>{{"$ ". number_format($ingresosExtraordinarios,0) }}</strong></label>
    <br>
    <label>Total egresos extraordinarios: <strong> {{"$ ". number_format($egresosExtraordinarios,0) }}</strong></label>
    <br>
    <label>Utilidades devengadas en el periodo de tiempo: <strong>{{"$ ". number_format($balance,0) }}</strong></label>
    <br>
    <label>Dinero de caja en el periodo de tiempo: <strong>{{"$ ". number_format($valorEnCaja,0) }}</strong></label>
</div>
<br>
<div>
    <div align="center" class="notice">Salsamentaria ZEA</div>
    <div align="center" class="notice">
        Fecha y hora de impresión: {{ $fechaActual }}
    </div>
</div>

</body>
</html>
