@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Productos granel</h1>
    </div>
    <br>

    <div class="container-fluid">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">{{session('success')}} <i
                    class="fas fa-fw fa-check-circle"></i></div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Detalle del producto</h3>
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
                            <input id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{old('nombre')}}" name="nombre" required autocomplete="nombre">
                            @error('nombre')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Tipo de producto:</label>

                        <div class="col-md-8">
                            <select id="producto_tipos_id" name="producto_tipos_id"
                                    class="form-control @error('producto_tipos_id') is-invalid @enderror"
                                    value="{{old('producto_tipos_id')}}" name="producto_tipos_id" required>
                                @foreach($tipos as $tipo)
                                    <option value="{{$tipo->Id}}">{{$tipo->Nombre}}</option>
                                @endforeach
                            </select>
                            @error('producto_tipos_id')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Costo por kilo (Kg):</label>

                        <div class="col-md-8">
                            <input id="costokilo" type="number"
                                   class="form-control @error('costokilo') is-invalid @enderror"
                                   value="{{old('costokilo')}}" name="costokilo" required autocomplete="costokilo">
                            @error('costokilo')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Utilidad por kilo (Kg):</label>

                        <div class="col-md-8">
                            <input id="utilidadkilo" type="number"
                                   class="form-control @error('utilidadkilo') is-invalid @enderror"
                                   value="{{old('utilidadkilo')}}" name="utilidadkilo" required
                                   autocomplete="utilidadkilo">
                            @error('utilidadkilo')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Precio del kilo:</label>
                        <div class="col-md-8">
                            <input id="preciokilo" type="number"
                                   class="form-control @error('preciokilo') is-invalid @enderror"
                                   value="{{old('preciokilo')}}" name="preciokilo" required autocomplete="preciokilo">
                            @error('preciokilo')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Gramos en stock:</label>
                        <div class="col-md-8">
                            <input id="stockgramos" type="number"
                                   class="form-control @error('stockgramos') is-invalid @enderror"
                                   value="{{old('stockgramos')}}" name="stockgramos" required
                                   autocomplete="stockgramos">
                            @error('stockgramos')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                </form>
                <br>
                <div class="row btn-toolbar justify-content-center" >

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="registrar" type="button" value="Registrar"
                               class="btn btn-primary container-fluid"/>
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
                <h3 class="m-0 font-weight-bold text-primary text-center">Productos unitarios registrados</h3>
            </div>
            <div class="card-body">
                @if(!$productos->isEmpty())
                    <table id="recurso" class="table table-bordered dt-responsive nowrap table-hover"
                           style="width:100%" cellspacing="0" data-page-length='5' data-name="recursos">
                        <thead>
                        <tr>
                            @foreach ($productos->get(0) as $key => $value)
                                <th>{{$key}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($productos as $registro)
                            <tr class="row-hover">
                                @foreach ($registro as $key => $value)
                                    <td class="text-center">{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <h3 align="center">No hay productos unitarios disponibles.</h3>
                @endif
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            let conf = {
                "columnDefs": [
                    {
                        targets: [6],
                        visible: false,
                        searchable: false
                    },
                    {
                        targets: [2, 4],
                        render: $.fn.dataTable.render.number(',', '.', 0,'$ ')
                    },
                    {
                        targets: 3, render: function (data, type, full, meta) {
                            return +data + '%';
                        }
                    }
                ]
            }
            $.extend(conf, options);
            let table = $('#recurso').DataTable(conf);
            $('#recurso tbody').on('click', 'tr', function () {
                document.getElementById('registrar').disabled = true;
                var data = table.row(this).data();
                document.getElementById('id').value = data[0];
                document.getElementById('nombre').value = data[1];
                document.getElementById('costokilo').value = data[2];
                document.getElementById('utilidadkilo').value = data[3];
                document.getElementById('preciokilo').value = data[4];
                document.getElementById('stockgramos').value = data[5];
                document.getElementById('producto_tipos_id').value = data[6];
            });

            $("#registrar").click(function () {
                document.form.action = "{{ route('productosgranel.crear') }}";
                document.form.submit();
            });

            $("#limpiar").click(function () {
                document.getElementById('id').value = "";
                document.getElementById('nombre').value = "";
                document.getElementById('costokilo').value = "";
                document.getElementById('utilidadkilo').value = "";
                document.getElementById('preciokilo').value = "";
                document.getElementById('stockgramos').value = "";
                document.getElementById('registrar').disabled = false;
            });

            $("#modificar").click(function () {
                document.form.action = "{{ route('productosgranel.actualizar') }}";
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
                            var url = "{{ route('productosgranel.borrar', ':id') }}";
                            document.form.action = url.replace(':id', document.getElementById('id').value);
                            document.form.submit();
                        }
                    });
            });

            $("#costokilo,#utilidadkilo").keyup(function () {
                var costo = parseInt(document.getElementById('costokilo').value, 10);
                var utilidad = parseInt(document.getElementById('utilidadkilo').value, 10);
                if (!isNaN(costo) && !isNaN(utilidad)) {
                    document.getElementById('preciokilo').value = Math.round(costo * (1 + utilidad / 100));
                }
            });
        });
    </script>
@endsection

