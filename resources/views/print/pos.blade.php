<style>
    @page {
        size: 79mm 600pt;
        margin: 5mm;
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
<head>
    <meta charset="utf-8">
</head>
<body>
<div align="center">
    <img style="width: 13mm; height: 13mm" src="{{ asset('favicon.png') }}" class="img-fluid" alt="Salsamentaria ZEA">
</div>
<div align="center" style="font-size:12px">{{$nombreEmpresa}}</div>
<div align="center" style="font-size:12px">{{$personaNatural}}</div>
<div align="center" style="font-size:12px">{{$NIT}}</div>
<div align="center" style="font-size:12px">{{$direccionEmpresa}}</div>
<div align="center" style="font-size:12px">{{$telefonoEmpresa}}</div>
<div align="center" style="font-size:12px">{{$emailEmpresa}}</div>
<br>

<div align="left" style="font-size:12px">
    <label class="negrita"> {{ $descripcion }} </label>
    <br>
    <label>Fecha: <strong>{{$fechaActual}}</strong></label>
    <br>
    <label> <strong>Datos del cliente</strong> </label>
    <br>
    <label> Nombre/razón social: <strong>{{$nombreParticipante}}</strong> </label>
    <br>
    <label> Teléfono: <strong>{{$telefonoParticipante}}</strong></label>
    <br>
    <label> Direccion: <strong>{{$direccionParticipante}}</strong> </label>
    <br>
    <label> Le atendió: <strong>{{$tituloEmpleado}}</strong> </label>
    <hr>
    <table align="center">
        <thead>
        <tr>
            <th>#</th>
            <th class="service">Producto</th>
            <th>Precio unit/kg</th>
            <th>Cant</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($registros as $registro)
            <tr>
                <td class="negrita" align="center">{{ $registro->numero }}</td>
                <td class="negrita" align="center">{{ $registro->nombre }}</td>
                <td class="negrita" align="center">{{ $registro->valorUnitario }}</td>
                <td class="negrita" align="center">{{ $registro->cantidad }}</td>
                <td class="negrita" align="center">{{ $registro->total }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold">
            <td class="negrita" align="center" colspan="4">Total</td>
            <td class="negrita" align="center">{{ $total }}</td>
        </tr>
        </tbody>
    </table>
    <hr>
</div>
<div align="center" style="font-size:12px">
    <div>Por favor conservar este tiquete</div>
    <div>Gracias por su compra</div>
</div>
</body>






