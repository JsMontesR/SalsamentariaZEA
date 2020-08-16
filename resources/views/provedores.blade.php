@extends('layouts.app')

@section('content')

    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Proveedores</h1>
    </div>
    <br>

    @if(session()->has('success'))
        <div class="alert alert-success" role="alert">{{session('success')}}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detalle del proveedor</h6>
        </div>
        <div class="card-body">
            <form id="form1" name="form1" method="POST">
                @csrf

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-left">Id:</label>

                    <div class="col-md-8">
                        <input readonly="readonly" id="id" class="form-control @error('id') is-invalid @enderror"
                               value="{{old('id')}}" name="id">
                        @error('id')
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-left">Nombre:</label>

                    <div class="col-md-8">
                        <input id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{old('nombre')}}" name="nombre" required autocomplete="nombreProveedor">
                        @error('nombre')
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-left">Teléfono</label>

                    <div class="col-md-8">
                        <input id="telefono" type="number" class="form-control @error('telefono') is-invalid @enderror"
                               value="{{old('telefono')}}" name="telefono" required autocomplete="telefonoProveedor">
                        @error('telefono')
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-left">Dirección:</label>
                    <div class="col-md-8">
                        <input id="direccion" class="form-control " name="direccion" value="{{old('direccion')}}"
                               required autocomplete="direccionProveedor">
                    </div>
                </div>
            </form>
            <br>
            <div class="d-flex justify-content-center">
                <div align="center" class="btn-toolbar" role="toolbar">
                    <br>
                    <div class="btn-group col-md">
                        <input id="registrar" type="button" value="Registrar" class="btn btn-primary"
                               onclick="registrarProveedor()"/>
                        <input type="button" value="Limpiar" class="btn btn-secondary" onclick="limpiarCampos()"/>
                    </div>
                    <br>
                    <div class="btn-group col-md">
                        <input type="button" value="Modificar" class="btn btn-warning" onclick="modificarCliente()"/>
                        <input type="button" value="Eliminar" class="btn btn-danger" onclick="eliminarCliente()"/>
                    </div>
                    <script type="text/javascript">


                    </script>


                </div>

            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Proveedores registrados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if(!$clientes->isEmpty())

                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" data-name="my_table"
                           data-page-length='5'>

                        <thead>
                        <tr>
                            <th>Seleccionar</th>
                            @foreach ($clientes->get(0) as $key => $value)

                                <th>{{$key}}</th>

                            @endforeach

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($clientes as $registro)
                            <tr>
                                <td align="center">
                                    <a id="{{$registro->Id}}" class="btn btn-secondary text-white" href="#page-top">
                                        <em class="fas fa-angle-up"></em>
                                        Ver
                                    </a>
                                </td>
                                @foreach ($registro as $key => $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Seleccionar</th>
                            @foreach ($clientes[0] as $key => $value)
                                <th>{{$key}}</th>
                            @endforeach
                        </tr>
                        </tfoot>

                    </table>
                @else
                    <h3 align="center">No hay clientes disponibles, intentelo más tarde</h3>
                @endif
            </div>
        </div>

    </div>
    </div>





    <br>

    </div>
    </div>
    </div>
    <script src="{{asset('public/assets/libs/js/proveedores.js')}}"
@endsection
