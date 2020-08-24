@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Clientes</h1>
    </div>
    <br>

    <div class="container-fluid">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">{{session('success')}} <i class="fas fa-fw fa-check-circle"></i></div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Detalle del cliente</h3>
            </div>
            <div class="card-body">
                <form id="form" name="form" method="POST">
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
                            <input id="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name')}}" name="name" required autocomplete="nameCliente">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Documento de identidad:</label>

                        <div class="col-md-8">
                            <input id="di" type="number"
                                   class="form-control @error('di') is-invalid @enderror"
                                   value="{{old('di')}}" name="di">
                            @error('di')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Teléfono celular:</label>
                        <div class="col-md-8">
                            <input id="celular" type="number"
                                   class="form-control @error('celular') is-invalid @enderror"
                                   value="{{old('celular')}}" name="celular">
                            @error('celular')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Teléfono fijo:</label>
                        <div class="col-md-8">
                            <input id="fijo" type="number"
                                   class="form-control @error('fijo') is-invalid @enderror"
                                   value="{{old('fijo')}}" name="fijo">
                            @error('fijo')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Correo electrónico:</label>
                        <div class="col-md-8">
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{old('email')}}" name="email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Dirección:</label>
                        <div class="col-md-8">
                            <input id="direccion"
                                   class="form-control @error('direccion') is-invalid @enderror"
                                   value="{{old('direccion')}}" name="direccion">
                            @error('direccion')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                </form>
                <br>
                <div class="d-flex justify-content-center">
                    <br>
                    <div class="row btn-toolbar" role="toolbar">

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 py-2">
                            <input id="registrar" type="button" value="Registrar"
                                   class="btn btn-primary container-fluid"/>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 py-2">
                            <input id="limpiar" type="button" value="Limpiar"
                                   class="btn btn-light text-dark container-fluid"/>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 py-2">
                            <input id="modificar" type="button" value="Modificar"
                                   class="btn btn-warning container-fluid"/>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 py-2">
                            <input id="eliminar" type="button" value="Eliminar" class="btn btn-danger container-fluid"/>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Clientes registrados</h3>
            </div>
            <div class="card-body">
                @if(!$clientes->isEmpty())
                    <table id="recurso" class="table table-bordered dt-responsive nowrap table-hover"
                           style="width:100%" cellspacing="0" data-page-length='5' data-name="recursos">
                        <thead>
                        <tr>
                            @foreach ($clientes->get(0) as $key => $value)
                                <th>{{$key}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($clientes as $registro)
                            <tr class="row-hover">
                                @foreach ($registro as $key => $value)
                                    <td class="text-center">{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <h3 align="center">No hay clientes disponibles, intentelo más tarde</h3>
                @endif
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            let conf = {
                "columnDefs": [
                    {
                        targets: [7, 8],
                        visible: false,
                        searchable: false
                    },
                    {
                        targets: [2],
                        render: $.fn.dataTable.render.number('.', '.', 0,)
                    }
                ]
            }
            $.extend(conf, options);
            let table = $('#recurso').DataTable(conf);
            $('#recurso tbody').on('click', 'tr', function () {
                document.getElementById('registrar').disabled = true;
                var data = table.row(this).data();
                document.getElementById('id').value = data[0];
                document.getElementById('name').value = data[1];
                document.getElementById('di').value = data[2];
                document.getElementById('celular').value = data[3];
                document.getElementById('fijo').value = data[4];
                document.getElementById('email').value = data[5];
                document.getElementById('direccion').value = data[6];
            });

            $("#registrar").click(function () {
                document.form.action = "{{ route('clientes.crear') }}";
                document.form.submit();
            });

            $("#limpiar").click(function () {
                document.getElementById('id').value = "";
                document.getElementById('name').value = "";
                document.getElementById('di').value = "";
                document.getElementById('celular').value = "";
                document.getElementById('fijo').value = "";
                document.getElementById('email').value = "";
                document.getElementById('direccion').value = "";
                document.getElementById('registrar').disabled = false;
            });

            $("#modificar").click(function () {
                document.form.action = "{{ route('clientes.actualizar') }}";
                document.form.submit();
            });

            $("#eliminar").click(function () {
                swal({
                    title: "¿Estas seguro?",
                    text: "¡Una vez borrado no será posible recuperarlo!",
                    icon: "warning",
                    dangerMode: true,
                    buttons: ["Cancelar","Borrar"]
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            var url = "{{ route('clientes.borrar', ':id') }}";
                            document.form.action = url.replace(':id', document.getElementById('id').value);
                            document.form.submit();
                        }
                    });
            });
        });
    </script>
@endsection

