<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Example 1</title>
    <link rel="stylesheet" href="{{asset('vendor/invoices/style.css')}}" media="all"/>
</head>
<body>
<header class="clearfix">
    <div id="logo">
        <img src="{{asset('favicon.png')}}">
    </div>
    <h1>{{ $descripcion }}</h1>
    <div style="float: right">
        <div>Salsamentaría ZEA</div>
        <div> {{ $direccionEmpresa }}</div>
        <div> {{ $telefonoEmpresa }}</div>
        <div> {{ $emailEmpresa }}</div>
    </div>
    <div style="float: left">
        <div><label class="bold">Cliente: </label>{{$nombreParticipante}}</div>
        <div><label class="bold">Fecha: </label>{{ $fecha }}</div>
    </div>
</header>
<main>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th class="service">Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($registros as $registro)
            <tr>
                <td>{{ $registro->numero }}</td>
                <td class="service">{{ $registro->nombre }}</td>
                <td class="unit">{{ $registro->valorUnitario }}</td>
                <td class="qty">{{ $registro->cantidad }}</td>
                <td class="total">{{ $registro->total }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" class="total">Total</td>
            <td class="total">{{ $total }}</td>
        </tr>
        </tbody>
    </table>
    <div id="notices">
        <div align="center" class="notice">Salsamentaria ZEA</div>
    </div>
</main>
<footer>
    Fecha de impresión {{ $fechaActual }}
</footer>
</body>
</html>
