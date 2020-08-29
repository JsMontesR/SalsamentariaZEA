@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Productos</h1>
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
                        <label id="label_nombre" class="col-md-4 col-form-label text-md-left">Nombre:</label>

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
                        <label id="label_categoria" class="col-md-4 col-form-label text-md-left">Categoría de
                            producto</label>

                        <div class="col-md-8">
                            <select id="categoria" name="categoria"
                                    class="form-control @error('categoria') is-invalid @enderror"
                                    value="{{old('categoria')}}" name="categoria" required>
                                <option disabled selected value>Seleccione una opción</option>
                                <option value="Unitario">Unitario</option>
                                <option value="Granel">Granel</option>
                            </select>
                            @error('categoria')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id="label_producto_tipos_id" class="col-md-4 col-form-label text-md-left">Tipo de
                            producto</label>

                        <div class="col-md-8">
                            <select id="producto_tipos_id" name="producto_tipos_id"
                                    class="form-control @error('producto_tipos_id') is-invalid @enderror"
                                    value="{{old('producto_tipos_id')}}" name="producto_tipos_id" required>
                                <option disabled selected value>Seleccione una opción</option>
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
                        <label id="label_costo" class="col-md-4 col-form-label text-md-left">Costo por
                            unidad/kg:</label>

                        <div class="col-md-8">
                            <input id="costo" type="number" class="form-control @error('costo') is-invalid @enderror"
                                   value="{{old('costo')}}" name="costo" required autocomplete="costo">
                            @error('costo')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id="label_utilidad" class="col-md-4 col-form-label text-md-left">Utilidad por
                            unidad/kg:</label>

                        <div class="col-md-8">
                            <input id="utilidad" type="number"
                                   class="form-control @error('utilidad') is-invalid @enderror"
                                   value="{{old('utilidad')}}" name="utilidad" required autocomplete="utilidad">
                            @error('utilidad')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id="label_precio" class="col-md-4 col-form-label text-md-left">Precio por
                            unidad/kg:</label>
                        <div class="col-md-8">
                            <input id="precio" type="number" class="form-control @error('precio') is-invalid @enderror"
                                   value="{{old('precio')}}" name="precio" required autocomplete="precio">
                            @error('precio')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id="label_stock" class="col-md-4 col-form-label text-md-left">Unidades/g en
                            stock:</label>
                        <div class="col-md-8">
                            <input id="stock" type="number" class="form-control @error('stock') is-invalid @enderror"
                                   value="{{old('stock')}}" name="stock" required autocomplete="stock">
                            @error('stock')
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
                    <h3 align="center">No hay productos disponibles.</h3>
                @endif
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {

            function caracterizarFormulario(tipo) {
                if (tipo == undefined) {
                    document.getElementById('label_costo').innerText = "Costo por unidad/kg:";
                    document.getElementById('label_utilidad').innerText = "Utilidad por unidad/kg:";
                    document.getElementById('label_precio').innerText = "Precio por unidad/kg:";
                    document.getElementById('label_stock').innerText = "Unidades/g en stock:";
                } else if (tipo == "Granel") {
                    document.getElementById('label_costo').innerText = "Costo por kilo (kg):";
                    document.getElementById('label_utilidad').innerText = "Utilidad por kilo (kg):";
                    document.getElementById('label_precio').innerText = "Precio por kilo (kg):";
                    document.getElementById('label_stock').innerText = "Gramos en stock (g):"
                } else if (tipo == "Unitario") {
                    document.getElementById('label_costo').innerText = "Costo por unidad:";
                    document.getElementById('label_utilidad').innerText = "Utilidad por unidad:";
                    document.getElementById('label_precio').innerText = "Precio por unidad:";
                    document.getElementById('label_stock').innerText = "Unidades en stock:"
                }
            }

            $("#categoria").change(function () {
                caracterizarFormulario($(this).val());
            })

            let conf = {
                "columnDefs": [
                    {
                        targets: [7],
                        visible: false,
                        searchable: false
                    },
                    {
                        targets: [3, 5],
                        render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                    },
                    {
                        targets: 4,
                        render: function (data, type, full, meta) {
                            return +data + '%';
                        }
                    },
                    {
                        targets: 6,
                        render: function (data, type, row) {
                            if (row[2] == "Granel") {
                                return +data + ' gramos';
                            } else if (row[2] == "Unitario") {
                                return +data + ' unidades';
                            }

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
                document.getElementById('categoria').value = data[2];
                document.getElementById('costo').value = data[3];
                document.getElementById('utilidad').value = data[4];
                document.getElementById('precio').value = data[5];
                document.getElementById('stock').value = data[6];
                document.getElementById('producto_tipos_id').value = data[7];
                caracterizarFormulario(data[2]);
            });

            $("#registrar").click(function () {
                document.form.action = "{{ route('productos.crear') }}";
                document.form.submit();
            });

            $("#limpiar").click(function () {
                document.getElementById('id').value = "";
                document.getElementById('nombre').value = "";
                document.getElementById('categoria').value = "";
                document.getElementById('costo').value = "";
                document.getElementById('utilidad').value = "";
                document.getElementById('precio').value = "";
                document.getElementById('stock').value = "";
                document.getElementById('producto_tipos_id').value = "";
                document.getElementById('registrar').disabled = false;
                caracterizarFormulario(undefined);
            });

            $("#modificar").click(function () {
                document.form.action = "{{ route('productos.actualizar') }}";
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
                            document.form.action = "{{ route('productos.borrar') }}";
                            document.form.submit();
                        }
                    });
            });

            $("#costo,#utilidad").keyup(function () {
                var costo = parseInt(document.getElementById('costo').value, 10);
                var utilidad = parseInt(document.getElementById('utilidad').value, 10);
                if (!isNaN(costo) && !isNaN(utilidad)) {
                    document.getElementById('precio').value = Math.round(costo * (1 + utilidad / 100));
                }
            });
        });
    </script>
@endsection

