<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Comprobante de nómina</title>
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
        <div><label class="bold">Fecha de la nómina: </label><strong>{{ $fecha }}</strong></div>
        <br>
        <div><label> <strong>Datos del empleado al que se pagó</strong> </label></div>
        <div><label class="bold">Nombre del empleado: </label><strong>{{$nombreParticipante}}</strong>
        </div>
        @if($diParticipante != null)
            <div><label class="bold">Documento de identidad del empleado: </label><strong>{{$diParticipante}}</strong>
            </div>
        @endif
        <div><label class="bold">Salario base del empleado: </label><strong>{{$valorBase}}</strong></div>
        <br>
        <div><label> <strong>Datos de la operación</strong> </label></div>
        <div><label class="bold">Valor de la nómina: </label><strong>{{$total}}</strong></div>
        <div><label class="bold">Dinero total abonado: </label><strong>{{$dineroAbonado}}</strong></div>
        <div><label class="bold">Saldo pendiente: </label><strong>{{$saldo}}</strong></div>

        @if($fechaDePago != null)
            <div><label class="bold">Fecha de pago: </label><strong>{{ $fechaDePago }}</strong></div>
        @elseif($fechaLimitePago != null)
            <div><label class="bold">Fecha límite de pago: </label><strong>{{ $fechaLimitePago }}</strong></div>
        @endif
        <div><label class="bold">Empleado que realizó la transacción: </label><strong>{{ $tituloEmpleado }}</strong></div>
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
