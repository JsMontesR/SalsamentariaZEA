<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Example 1</title>
    <link rel="stylesheet" href="{{asset('vendor/invoices/style.css')}}" media="all"/>
</head>
<body>
<header>
    <div id="logo">
        <img src="{{asset('favicon.png')}}">
    </div>
    <h1>{{ $descripcion }}</h1>
    <div style="float: right">
        <div>{{$nombreEmpresa}}</div>
        <div>{{$personaNatural}}</div>
        <div>{{$NIT}}</div>
        <div>{{$detalle}}</div>
        <div>{{$direccionEmpresa}}</div>
        <div>{{$telefonoEmpresa}}</div>
        <div>{{$emailEmpresa}}</div>
    </div>
    <div style="float: left">
        <div><label class="bold">Fecha de la venta: </label><strong>{{ $fecha }}</strong></div>
        <div><label> <strong>Datos del cliente</strong> </label></div>
        <div><label class="bold">Nombre/razón social: </label>{{$nombreParticipante}}</div>
        <div><label class="bold">Teléfono: </label>{{ $telefonoParticipante }}</div>
        <div><label class="bold">Dirección: </label>{{ $direccionParticipante }}</div>

        @if($fechaDePago != null)
            <div><label class="bold">Fecha de pago: </label><strong>{{ $fechaDePago }}</strong></div>
        @elseif($fechaLimitePago != null)
            <div><label class="bold">Fecha límite de pago: </label><strong>{{ $fechaLimitePago }}</strong></div>
        @endif
        @if($lugarEntrega != null)
            <div><label class="bold">Lugar de entrega: </label><strong>{{ $lugarEntrega }}</strong></div>
        @endif
    </div>
</header>
<br>
<br>
<h3>Detalle de productos:</h3>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th class="service">Producto</th>
        <th>Precio unit/kg</th>
        <th>Cantidad</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($registros as $registro)
        <tr>
            <td class="id">{{ $registro->numero }}</td>
            <td class="service">{{ $registro->nombre }}</td>
            <td class="unit">{{ $registro->valorUnitario }}</td>
            <td class="qty">{{ $registro->cantidad }}</td>
            <td class="total">{{ $registro->total }}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold">
        <td class="grand total" colspan="4">Total</td>
        <td class="grand total">{{ $total }}</td>
    </tr>
    </tbody>
</table>
<div>
    <label> Dinero total abonado: <strong>{{$dineroAbonado}}</strong></label>
</div>
<div>
    <label> Saldo pendiente: <strong>{{$saldo}}</strong> </label>
</div>

<div id="notices">
    <div align="center" class="notice">Salsamentaria ZEA</div>
    <div align="center" class="notice">
        Fecha y hora de impresión: {{ $fechaActual }}
    </div>
</div>

</body>
</html>
