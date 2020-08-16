@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Proveedores</h1>
    </div>
    <br>
    @if(session()->has('success'))
        <div class="alert alert-success" role="alert">{{session('success')}}</div>
    @endif
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Detalle del proveedor</h3>
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
                            <input id="telefono" type="number"
                                   class="form-control @error('telefono') is-invalid @enderror"
                                   value="{{old('telefono')}}" name="telefono" required
                                   autocomplete="telefonoProveedor">
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
                    <br>
                    <div class="row btn-toolbar" role="toolbar">

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 py-2">
                            <input id="registrar" type="button" value="Registrar"
                                   class="btn btn-primary container-fluid"
                                   onclick="registrarProveedor()"/>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 py-2">
                            <input type="button" value="Limpiar" class="btn btn-secondary container-fluid"
                                   onclick="limpiarCampos()"/>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 py-2">
                            <input type="button" value="Modificar" class="btn btn-warning container-fluid"
                                   onclick="modificarCliente()"/>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 py-2">
                            <input type="button" value="Eliminar" class="btn btn-danger container-fluid"
                                   onclick="eliminarCliente()"/>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Proveedores registrados</h3>
            </div>
            <div class="card-body">
                @if(!$proveedores->isEmpty())
                    <table id="example" class="table table-striped table-bordered dt-responsive nowrap"
                           style="width:100%" cellspacing="0" data-page-length='5'>
                        <thead>
                        <tr>
                            @foreach ($proveedores->get(0) as $key => $value)
                                <th>{{$key}}</th>
                            @endforeach
                            <th>Seleccionar</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($proveedores as $registro)
                            <tr>
                                @foreach ($registro as $key => $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                                <td align="center">
                                    <a id="{{$registro->Id}}" class="btn btn-secondary text-white" href="#page-top">
                                        <em class="fas fa-angle-up"></em>
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <h3 align="center">No hay proveedores disponibles, intentelo más tarde</h3>
                @endif
            </div>
        </div>
    </div>

    {{--    <script src="{{asset('public/assets/libs/js/proveedores.js')}}"--}}
@endsection
