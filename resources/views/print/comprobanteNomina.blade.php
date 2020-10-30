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
        <div>{{$nombreEmpresa}}</div>
        <div>{{$personaNatural}}</div>
        <div>{{$NIT}}</div>
        <div>{{$direccionEmpresa}}</div>
        <div>{{$telefonoEmpresa}}</div>
        <div>{{$emailEmpresa}}</div>
    </div>
    <div style="float: left">
        <div><label class="bold">Fecha del servicio: </label><strong>{{ $fecha }}</strong></div>
        <div><label> <strong>Datos del servicio</strong> </label></div>
        <div><label class="bold">Servicio: </label><strong>{{$numeroServicio}} - {{$nombreServicio}}</strong></div>
        <div><label class="bold">Valor de referencia del servicio: </label><strong>{{$valorBase}}</strong></div>
        <div><label class="bold">Valor del servicio: </label><strong>{{$total}}</strong></div>
        <div><label class="bold">Dinero total abonado: </label><strong>{{$dineroAbonado}}</strong></div>
        <div><label class="bold">Saldo pendiente: </label><strong>{{$saldo}}</strong></div>

        @if($fechaDePago != null)
            <div><label class="bold">Fecha de pago: </label><strong>{{ $fechaDePago }}</strong></div>
        @elseif($fechaLimitePago != null)
            <div><label class="bold">Fecha límite de pago: </label><strong>{{ $fechaLimitePago }}</strong></div>
        @endif
        <div><label class="bold">Empleado que realizó la transacción: </label>{{ $tituloEmpleado }}</div>
    </div>
</header>
<br>
<div id="notices">
    <div align="center" class="notice">Salsamentaria ZEA</div>
    <div align="center" class="notice">
        Fecha y hora de impresión: {{ $fechaActual }}
    </div>
</div>

</body>
</html>
