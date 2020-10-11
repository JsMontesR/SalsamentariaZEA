<style>
    @page {
        size: 55mm 600pt;
        margin: 5mm;
    }

    table {
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid black;
        font-size: 9px;
    }

    hr {
        background-color: #403d3d;
        border: 0 none;
        color: #eee;
        height: 1px;
    }
</style>
<body>
<div align="center">
    <img style="width: 13mm; height: 13mm" src="{{ asset('favicon.png') }}" class="img-fluid" alt="Salsamentaria ZEA">
</div>
<div align="center" style="font-size:12px">{{$nombreEmpresa}}</div>
<div align="center" style="font-size:12px">{{$direccionEmpresa}}</div>
<div align="center" style="font-size:12px">{{$telefonoEmpresa}}</div>
<div align="center" style="font-size:12px">{{$emailEmpresa}}</div>
<br>

<div align="left" style="font-size:12px">
    <label> {{ $descripcion }} </label>
    <br>
    <label>Fecha: {{$fechaActual}}</label>
    <br>
    <label> Cliente: {{$nombreParticipante}} </label>
    <br>
    <label> Le atendió: {{$tituloEmpleado}} </label>
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
                <td align="center">{{ $registro->numero }}</td>
                <td align="center">{{ $registro->nombre }}</td>
                <td align="center">{{ $registro->valorUnitario }}</td>
                <td align="center">{{ $registro->cantidad }}</td>
                <td align="center">{{ $registro->total }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold">
            <td align="center" style="font-weight: bold" colspan="4">Total</td>
            <td align="center" style="font-weight: bold">{{ $total }}</td>
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






