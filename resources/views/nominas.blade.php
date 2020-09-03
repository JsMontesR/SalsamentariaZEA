@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Nóminas</h1>
    </div>
    <br>

    <div class="container-fluid">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">{{session('success')}} <i
                    class="fas fa-fw fa-check-circle"></i></div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Detalle de la nómina</h3>
            </div>
            <div class="card-body">
                <form id="form" name="form" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label id="label_id" class="col-md-4 col-form-label text-md-left">Id:</label>
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
                        <label class="col-md-4 col-form-label text-md-left">Id del empleado:</label>

                        <div class="col-md-8">
                            <input id="empleado_id" class="form-control @error('empleado_id') is-invalid @enderror"
                                   value="{{old('empleado_id')}}" name="empleado_id" required
                                   autocomplete="empleado_id">
                            @error('empleado_id')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="table-responsive">
                                @if(!$empleados->isEmpty())
                                    <table id="empleados"
                                           class="table table-bordered dt-responsive nowrap table-hover"
                                           style="width:100%" cellspacing="0" data-page-length='5'>
                                        <thead>
                                        <tr>
                                            @foreach ($empleados->get(0) as $key => $value)
                                                <th>{{$key}}</th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($empleados as $registro)
                                            <tr class="row-cursor-hand">
                                                @foreach ($registro as $key => $value)
                                                    <td class="text-center">{{ $value }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <h3 align="center">No hay empleados disponibles.</h3>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Nombre del empleado:</label>

                        <div class="col-md-8">
                            <input id="nombre" readonly="readonly" class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{old('nombre')}}" name="nombre">
                            @error('nombre')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Documento de identidad:</label>
                        <div class="col-md-8">
                            <input id="di" readonly="readonly" class="form-control @error('di') is-invalid @enderror"
                                   value="{{old('di')}}" name="di">
                            @error('di')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Salario base:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input id="salario" type="number" readonly="readonly"
                                   class="form-control @error('salario') is-invalid @enderror"
                                   value="{{old('salario')}}" name="salario">
                            @error('salario')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Valor a pagar:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input id="valor" type="number"
                                   class="form-control @error('valor') is-invalid @enderror"
                                   value="{{old('valor')}}" name="valor" required="required">
                            @error('valor')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                </form>
                <br>
                <div class="row btn-toolbar justify-content-center">

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="pagar" type="button" value="Pagar"
                               class="btn btn-success container-fluid"/>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="limpiar" type="button" value="Limpiar"
                               class="btn btn-light text-dark container-fluid"/>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="modificar" type="button" value="Modificar"
                               class="btn btn-warning container-fluid"/>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="eliminar" type="button" value="Eliminar" class="btn btn-danger container-fluid"/>
                    </div>

                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Nóminas registradas</h3>
            </div>
            <div class="card-body">
                @if(!$nominas->isEmpty())
                    <table id="recurso" class="table table-bordered dt-responsive nowrap table-hover"
                           style="width:100%" cellspacing="0" data-page-length='5' data-name="recursos">
                        <thead>
                        <tr>
                            @foreach ($nominas->get(0) as $key => $value)
                                <th>{{$key}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($nominas as $registro)
                            <tr class="row-cursor-hand">
                                @foreach ($registro as $key => $value)
                                    <td class="text-center">{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <h3 align="center">No hay nóminas disponibles.</h3>
                @endif
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {

            let conf = {
                "columnDefs": [
                    {
                        targets: [1, 4],
                        visible: false,
                        searchable: false
                    },
                    {
                        targets: [5],
                        render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                    },
                    {
                        targets: [4],
                        render: $.fn.dataTable.render.number('.', '.', 0)
                    }
                ]
            }
            $.extend(conf, options);
            let table = $('#recurso').DataTable(conf);
            $('#recurso tbody').on('click', 'tr', function () {
                document.getElementById('pagar').disabled = true;
                let data = table.row(this).data();
                document.getElementById('id').value = data[0];
                document.getElementById('empleado_id').value = data[1];
                document.getElementById('nombre').value = data[2];
                document.getElementById('di').value = data[3];
                document.getElementById('salario').value = data[4];
                document.getElementById('valor').value = data[5];
            });

            let empleados_table = $('#empleados').DataTable(options);
            $('#empleados tbody').on('click', 'tr', function () {
                let data = empleados_table.row(this).data();
                document.getElementById('empleado_id').value = data[0];
                document.getElementById('nombre').value = data[1];
                document.getElementById('di').value = data[2];
                document.getElementById('salario').value = data[3];
                document.getElementById('valor').value = data[3];
            });

            $("#registrar").click(function () {
                document.form.action = "{{ route('nominas.pagar') }}";
                document.form.submit();
            });

            $("#pagar").click(function () {
                document.form.action = "{{ route('nominas.pagar') }}";
                document.form.submit();
            });

            $("#limpiar").click(function () {
                document.getElementById('id').value = "";
                document.getElementById('empleado_id').value = "";
                document.getElementById('nombre').value = "";
                document.getElementById('di').value = "";
                document.getElementById('salario').value = "";
                document.getElementById('valor').value = "";
                document.getElementById('pagar').disabled = false;
            });

            $("#modificar").click(function () {
                document.form.action = "{{ route('nominas.actualizar') }}";
                document.form.submit();
            });

            $("#eliminar").click(function () {
                swal({
                    title: "¿Estas seguro?",
                    text: "¡Una vez borrado no será posible recuperarlo!",
                    icon: "warning",
                    dangerMode: true,
                    buttons: ["Cancelar", "Borrar"]
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            document.form.action = "{{ route('nominas.borrar') }}";
                            document.form.submit();
                        }
                    });
            });
        });
    </script>
@endsection

