@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Entradas a inventario</h1>
    </div>
    <br>

    <div class="container-fluid">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">{{session('success')}} <i
                    class="fas fa-fw fa-check-circle"></i></div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Detalle de la entrada</h3>
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
                        <label class="col-md-4 col-form-label text-md-left">Proveedor:</label>

                        <div class="col-md-8">
                            <input id="proveedor_id" class="form-control @error('proveedor_id') is-invalid @enderror"
                                   value="{{old('proveedor_id')}}" name="proveedor_id" required>
                            @error('proveedor_id')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="table-responsive">
                                @if(!$proveedors->isEmpty())
                                    <table id="proveedores"
                                           class="table table-bordered dt-responsive nowrap table-hover"
                                           style="width:100%" cellspacing="0" data-page-length='5'>
                                        <thead>
                                        <tr>
                                            @foreach ($proveedors->get(0) as $key => $value)
                                                <th>{{$key}}</th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($proveedors as $registro)
                                            <tr class="row-hover">
                                                @foreach ($registro as $key => $value)
                                                    <td class="text-center">{{ $value }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <h3 align="center">No hay proveedores disponibles.</h3>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Productos:</label>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 p-3">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h3 class="m-0 font-weight-bold text-primary text-center">Productos disponibles</h3>
                                </div>
                                <div class="card-body">
                                    @if(!$productos->isEmpty())
                                        <table id="productos_table"
                                               class="table table-bordered dt-responsive nowrap table-hover"
                                               style="width:100%" cellspacing="0" data-page-length='5'
                                               data-name="recursos">
                                            <thead>
                                            <tr>
                                                @foreach ($productos->get(0) as $key => $value)
                                                    @if($key == "Nombre")
                                                        <th>Agregar</th>
                                                    @endif
                                                    <th>{{$key}}</th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($productos as $registro)
                                                <tr class="row-hover">
                                                    @foreach ($registro as $key => $value)
                                                        @if($key == "Nombre")
                                                            <td>
                                                                <input name='btn_agregar_productos_tabla' type='button'
                                                                       value='Agregar'
                                                                       class='btn btn-success container-fluid'/>
                                                            </td>
                                                        @endif
                                                        <td>{{ $value }}</td>
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

                        {{--                        <input id="productos_entrada" name="productos_entrada[]" type="hidden" required/>--}}

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 p-3">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h3 class="m-0 font-weight-bold text-primary text-center">Productos de la
                                        entrada</h3>
                                </div>
                                <div class="card-body">
                                    @if(!$productos->isEmpty())
                                        <table id="productos_entrada_table"
                                               class="table table-bordered dt-responsive nowrap table-hover"
                                               style="width:100%" cellspacing="0" data-page-length='5'
                                               data-name="recursos">
                                            <thead>
                                            <tr>

                                                @foreach ($productos->get(0) as $key => $value)
                                                    @if($key == "Nombre")
                                                        <th>Eliminar</th>
                                                    @endif
                                                    <th>{{$key}}</th>
                                                @endforeach
                                                <th>Cantidad (Unidades/g)</th>
                                                <th>Precio total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    @else
                                        <h3 align="center">No hay productos disponibles.</h3>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Fecha límite de pago:</label>

                        <div class="col-md-8">
                            <input id="fechapago" type="date"
                                   class="form-control @error('fechapago') is-invalid @enderror"
                                   value="{{old('fechapago')}}" name="fechapago" required>
                            @error('fechapago')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Fecha de pago:</label>

                        <div class="col-md-8">
                            <input id="fechapagado" type="date" readonly="readonly"
                                   class="form-control @error('fechapagado') is-invalid @enderror"
                                   value="{{old('fechapagado')}}" name="fechapagado" required>
                            @error('fechapagado')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Costo:</label>
                        <div class="col-md-8">
                            <input id="costo" type="number"
                                   class="form-control @error('costo') is-invalid @enderror"
                                   value="{{old('costo')}}" name="costo" required autocomplete="costo">
                            @error('costo')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>
                </form>
                <br>

                <br>
                <div class="row btn-toolbar justify-content-center">

                    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="registrar" type="button" value="Registrar"
                               class="btn btn-primary container-fluid"/>
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="pagar" type="button" value="Pagar"
                               class="btn btn-success container-fluid"/>
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="limpiar" type="button" value="Limpiar"
                               class="btn btn-light text-dark container-fluid"/>
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="modificar" type="button" value="Modificar"
                               class="btn btn-warning container-fluid"/>
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="eliminar" type="button" value="Eliminar" class="btn btn-danger container-fluid"/>
                    </div>

                </div>

            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Entradas registradas</h3>
            </div>
            <div class="card-body">
                @if(!$entradas->isEmpty())
                    <table id="recurso" class="table table-bordered dt-responsive nowrap table-hover"
                           style="width:100%" cellspacing="0" data-page-length='5' data-name="recursos">
                        <thead>
                        <tr>
                            @foreach ($entradas->get(0) as $key => $value)
                                <th>{{$key}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($entradas as $registro)
                            <tr class="row-hover">
                                @foreach ($registro as $key => $value)
                                    <td class="text-center">{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <h3 align="center">No hay entradas disponibles.</h3>
                @endif
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            let conf = {
                "columnDefs": [
                    {
                        targets: [1],
                        visible: false,
                        searchable: false
                    },
                    {
                        targets: [6],
                        render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                    },
                ]
            }
            let formrowtable = {
                'columnDefs': [
                    {
                        'targets': [5, 6],
                        'render': function (data, type, row, meta) {
                            if (type === 'display') {
                                var api = new $.fn.dataTable.Api(meta.settings);

                                var $el = $('input, select, textarea', api.cell({
                                    row: meta.row,
                                    column: meta.col
                                }).node());

                                var $html = $(data).wrap('<div/>').parent();

                                if ($el.prop('tagName') === 'INPUT') {
                                    $('input', $html).attr('value', $el.val());
                                    if ($el.prop('checked')) {
                                        $('input', $html).attr('checked', 'checked');
                                    }
                                } else if ($el.prop('tagName') === 'TEXTAREA') {
                                    $('textarea', $html).html($el.val());

                                } else if ($el.prop('tagName') === 'SELECT') {
                                    $('option:selected', $html).removeAttr('selected');
                                    $('option', $html).filter(function () {
                                        return ($(this).attr('value') === $el.val());
                                    }).attr('selected', 'selected');
                                }

                                data = $html.html();
                            }

                            return data;
                        }
                    }
                ],

                'responsive': true
            }

            $.extend(conf, options);
            $.extend(formrowtable, options);
            let table = $('#recurso').DataTable(conf);
            let tablaProveedores = $('#proveedores').DataTable(options);
            let productos_entrada_table = $('#productos_entrada_table').DataTable(formrowtable);
            let productos_table = $('#productos_table').DataTable(options);

            $('#recurso tbody').on('click', 'tr', function () {
                document.getElementById('registrar').disabled = true;
                let data = table.row(this).data();
                document.getElementById('id').value = data[0];
                document.getElementById('proveedor_id').value = data[1];
                document.getElementById('fechapago').value = data[4];
                document.getElementById('fechapagado').value = data[5];
                document.getElementById('costo').value = data[6];
                //llamada ajax
            });

            $('#proveedores tbody').on('click', 'tr', function () {
                var data = tablaProveedores.row(this).data();
                document.getElementById('proveedor_id').value = data[0];
            });

            // Update original input/select on change in child row
            $('#productos_entrada_table tbody').on('keyup change', '.child input, .child select, .child textarea', function (e) {
                var $el = $(this);
                var rowIdx = $el.closest('ul').data('dtr-index');
                var colIdx = $el.closest('li').data('dtr-index');
                var cell = productos_entrada_table.cell({row: rowIdx, column: colIdx}).node();
                $('input, select, textarea', cell).val($el.val());
                if ($el.is(':checked')) {
                    $('input', cell).prop('checked', true);
                } else {
                    $('input', cell).removeProp('checked');
                }
            });

            $(document).on('click', '[name="btn_eliminar_productos_entrada_table"]', function () {
                let row = $(this).closest('tr');
                let data = productos_entrada_table.row(row).data();
                let newRow = [];
                newRow.push(data[0]);
                newRow.push("<input name='btn_agregar_productos_tabla' type='button' value='Agregar' class='btn btn-success container-fluid'/>")
                newRow.push(data[2]);
                newRow.push(data[3]);
                newRow.push(data[4]);
                productos_table.row.add(newRow).draw(false);
                productos_entrada_table.row(row).remove().draw();
            });

            $(document).on('click', '[name="btn_agregar_productos_tabla"]', function () {
                let row = $(this).closest('tr');
                let data = productos_table.row(row).data();
                let newRow = [];
                newRow.push(data[0]);
                newRow.push("<input name='btn_eliminar_productos_entrada_table' type='button' value='Eliminar' class='btn btn-warning container-fluid'/>");
                newRow.push(data[2]);
                newRow.push(data[3]);
                newRow.push(data[4]);
                let tipoDeCantidad = "";
                if (data[2] == "Unitario") {
                    tipoDeCantidad = "Cantidad en unidades";
                } else {
                    tipoDeCantidad = "Cantidad en gramos";
                }
                newRow.push("<div class='form-group mb-1'><input id='cantidad_producto_entrada" + data[0] + "' class='form-control' type='number' placeholder='" + tipoDeCantidad + "'/></div>");
                newRow.push("<div class='form-group mb-1'><input id='precio_producto_entrada" + data[0] + "' class='form-control' type='number' placeholder='Costo total'/></div>");
                productos_entrada_table.row.add(newRow).draw(false);
                productos_table.row(row).remove().draw();
            });

            $("#registrar").click(function () {
                let productos_entrada_array = [];
                productos_entrada_table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    let data = this.data();
                    let id = data[0];
                    $("<input />").attr("type", "hidden")
                        .attr("name", "productos_entrada[]")
                        .attr("value", JSON.stringify({
                            "id": id,
                            "cantidad": $('#cantidad_producto_entrada' + id).val(),
                            "precio": $('#precio_producto_entrada' + id).val()
                        }))
                            .appendTo("#form");
                });

                document.form.action = "{{ route('entradas.crear') }}";
                document.form.submit();
            });

            $("#limpiar").click(function () {
                document.getElementById('id').value = "";
                document.getElementById('proveedor_id').value = "";
                document.getElementById('fechapagado').value = "";
                document.getElementById('fechapago').value = "";
                document.getElementById('costo').value = "";
                document.getElementById('registrar').disabled = false;

                //limpiar carrito
            });

            $("#modificar").click(function () {
                document.form.action = "{{ route('entradas.actualizar') }}";
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
                            document.form.action = "{{ route('entradas.borrar') }}";
                            document.form.submit();
                        }
                    });
            });

        });
    </script>
@endsection

