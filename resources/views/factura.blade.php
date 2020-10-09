<!doctype html>
<html lang="es">

<head>
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/circular-std/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icon-css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/css/select.dataTables.min.css') }}">
    <title>Factura</title>
</head>
<body>
<div class="card">
    <div class="card-header p-4">
        <a class="pt-2 d-inline-block">{{ $concepto }}</a>

        <div class="float-right"><h3 class="mb-0">{{ $descripcion }}</h3>
            {{ $fecha }}</div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-sm-6">
                <h5 class="mb-3">{{ $tituloParticipante }}</h5>
                <h3 class="text-dark mb-1">{{ $nombreParticipante }}</h3>

                <div> {{ $direccionParticipante }}</div>
                <div> {{ $celularParticipante }}</div>
                <div> {{ $fijoParticipante }}</div>
                <div> {{ $emailParticipante }}</div>

            </div>
            <div class="col-sm-6">
                <h5 class="mb-3">Empleado:</h5>
                <h3 class="text-dark mb-1">{{ $tituloEmpleado }}</h3>
                <div> {{ $direccionEmpresa }}</div>
                <div> {{ $telefonoEmpresa }}</div>
                <div> {{ $emailEmpresa }}</div>
            </div>
        </div>
        <div class="table-responsive-sm">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="center">#</th>
                    <th>Artículo</th>
                    <th class="right">Costo unitario/Kg</th>
                    <th class="right">Tipo</th>
                    <th class="center">Cantidad</th>
                    <th class="right">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($registros as $registro)
                    <tr>
                        <td class="center"> {{ $registro->numero }}</td>
                        <td class="left strong"> {{ $registro->nombre }}</td>
                        <td class="right">{{ $registro->valorUnitario }}</td>
                        <td class="center">{{ $registro->cantidad }}</td>
                        <td class="right">{{ $registro->total }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-5">
            </div>
            <div class="col-lg-4 col-sm-5 ml-auto">
                <table class="table table-clear">
                    <tbody>
                    {{--                            <tr>--}}
                    {{--                                <td class="left">--}}
                    {{--                                    <strong class="text-dark">Subtotal</strong>--}}
                    {{--                                </td>--}}
                    {{--                                <td class="right">$28,809,00</td>--}}
                    {{--                            </tr>--}}
                    {{--                            <tr>--}}
                    {{--                                <td class="left">--}}
                    {{--                                    <strong class="text-dark">Discount (20%)</strong>--}}
                    {{--                                </td>--}}
                    {{--                                <td class="right">$5,761,00</td>--}}
                    {{--                            </tr>--}}
                    {{--                            <tr>--}}
                    {{--                                <td class="left">--}}
                    {{--                                    <strong class="text-dark">VAT (10%)</strong>--}}
                    {{--                                </td>--}}
                    {{--                                <td class="right">$2,304,00</td>--}}
                    {{--                            </tr>--}}
                    <tr>
                        <td class="left">
                            <strong class="text-dark">Total</strong>
                        </td>
                        <td class="right">
                            <strong class="text-dark">{{ $total }}</strong>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white">
        <p class="mb-0">Salsamentaria ZEA</p>
    </div>
</div>
</body>
</html>

