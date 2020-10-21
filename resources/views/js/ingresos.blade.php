<script>
    $(document).ready(function () {
        let btnAgregar = "<input name='btn_agregar_productos_tabla' type='button' value='Agregar' class='btn btn-success container-fluid'/>";

        darFormatoNumerico();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function darFormatoNumerico() {
            $('.money').mask('#.##0', {reverse: true});
        }

        function crearEstructuraDeProductos() {
            let arr = [];
            productos_ingreso_table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                let data = this.data();
                let id = data['id'];
                arr.push({
                    id: id,
                    cantidad: $('#cantidad_producto_tabla_ingreso' + id).cleanVal()
                });
            });
            return arr;
        }

        function limpiarFormulario() {
            document.getElementById('id').value = "";
            document.getElementById('valor').value = "";
            $('#recurso tr').removeClass("selected");
            $('#clientes tr').removeClass("selected");
            vacearProductosIngreso();
            $('#productos_container').show();
            document.getElementById('registrar').disabled = false;
            document.getElementById('eliminar').disabled = true;
            productos_table.ajax.reload()
        }

        function vacearProductosIngreso() {
            productos_ingreso_table.clear().draw();
        }

        function quitarProductoIngreso(row) {
            productos_ingreso_table.row(row).remove().draw();
        }

        function isFilaEnTablaIngreso(newRow) {
            let data = productos_ingreso_table.rows().data();
            let existe = false;
            data.each(function (value) {
                if (value["id"] == newRow["id"]) {
                    existe = true;
                    return false;
                }
            });
            return existe;
        }

        function crearFilaTablaIngreso(data) {
            let tipoDeCantidad = "";
            let emoji = "";
            let activated = "";
            let cantidad = "";
            let costo = "";

            if (data['categoria'] == "Unitario") {
                tipoDeCantidad = "# de unidades";
                emoji = "📦";
            } else {
                tipoDeCantidad = "# de kilos";
                emoji = "🌾";
            }
            if (data['pivot'] != undefined) {
                cantidad = "readonly value=" + data['pivot']['cantidad']
                costo = "readonly value=" + data['pivot']['costo'];
                activated = "disabled";
            }
            return $.extend({
                'btnEliminar': "<input " + activated + " name='btn_eliminar_productos_tabla_ingreso' type='button' value='Eliminar' class='btn btn-warning container-fluid'/>",
                'cantidad': "<div class='input-group mb-1'><div class='input-group-prepend'><span class='input-group-text'>" + emoji + "</span></div><input " + cantidad + " id='cantidad_producto_tabla_ingreso" + data['id'] + "' name='cantidad_producto_tabla_ingreso" + data['id'] + "' precio=" + data['precio'] + " class='money form-control' type='text' placeholder='" + tipoDeCantidad + "'/></div>"
            }, data)
        }

        function cargarProductosIngreso(productos) {
            for (i in productos) {
                let newRow = crearFilaTablaIngreso(productos[i]);
                productos_ingreso_table.row.add(newRow).draw();
                productos_ingreso_table.responsive.rebuild();
                productos_ingreso_table.responsive.recalc();
            }
            darFormatoNumerico();
        }

        function renderChange(data, type, row, meta) {
            if (type === 'display') {
                let api = new $.fn.dataTable.Api(meta.settings);
                let $el = $('input, select, textarea', api.cell({
                    row: meta.row,
                    column: meta.col
                }).node());

                let $html = $(data).wrap('<div/>').parent();

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


        function cargarIngreso(row) {
            limpiarFormulario();
            let data = table.row(row).data();
            document.getElementById('id').value = data['id'];
            $('[name="valor"]').val(data['valor']).trigger('input');
            $('#productos_container').hide();
            cargarProductosIngreso(data['productos']);
            $(row).addClass("selected");
            document.getElementById('registrar').disabled = true;
            document.getElementById('eliminar').disabled = false;
        }

        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            processing: true,
            ajax: "/api/ingresos/listar",
            columns: [
                {data: 'id', name: 'ingresos.id', title: 'Id', className: "text-center font-weight-bold"},
                {
                    data: 'empleado.name',
                    name: 'empleado.name',
                    title: 'Empleado que ingresó',
                    className: "text-center",
                    orderable: false
                },
                {
                    data: 'valor',
                    title: 'Valor ingresado',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'costo',
                    title: 'Costo total ingresado en productos',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'created_at',
                    title: 'Fecha de creación',
                    className: "text-center"
                }
            ]
        }, options));

        $('#recurso thead th').each(function () {
            var title = $(this).text();
            let id = "";
            if (title == "Id") {
                id = 'id = "specific"'
            }
            $(this).html(title + ' <input ' + id + 'type="text" class="col-search-input form-control-sm" placeholder="Buscar" />');
        });
        table.columns().every(function (index) {
            var col = this;
            $('input', this.header()).on('keyup', function () {
                if (col.search() !== this.value) {
                    col.search(this.value).draw();
                }
            });
        });

        let productos_table = $('#productos_table').DataTable($.extend({
            serverSide: true,
            ajax: '/api/productos/listar',
            columns: [
                {data: 'id', title: 'Id', className: "text-center"},
                {
                    data: null,
                    title: 'Agregar',
                    className: "text-center",
                    searchable: false,
                    render: function () {
                        return btnAgregar;
                    }
                },
                {data: 'nombre', title: 'Nombre', className: "text-center font-weight-bold"},
                {data: 'categoria', title: 'Categoría', className: "text-center"},
                {
                    data: 'costo',
                    title: 'Costo Unitario/Kg',
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ '),
                    className: "text-center"
                },
                {
                    data: 'utilidad',
                    title: 'Utilidad Unitaria/Kg',
                    className: "text-center",
                    render: function (data, type, full, meta) {
                        return +data + '%';
                    }
                },
                {
                    data: 'precio',
                    title: 'Precio Unitario/Kg',
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ '),
                    className: "text-center"
                },
                {
                    data: 'stock', title: 'Unidades/g en stock', render: function (data, type, row) {
                        if (row["categoria"] == "Granel") {
                            return +data + ' gramos';
                        } else if (row["categoria"] == "Unitario") {
                            return +data + ' unidades';
                        }

                    }, className: "text-center"
                },
                {
                    data: 'tipo.id',
                    title: 'Id de tipo',
                    visible: false,
                    searchable: false,
                    className: "text-center"
                },
                {data: 'tipo.nombre', title: 'Tipo', className: "text-center"},
                {data: 'created_at', title: 'Fecha de creación', className: "text-center"},
                {data: 'updated_at', title: 'Fecha de actualización', className: "text-center"},
            ],
            responsive: true
        }, options));

        let productos_ingreso_table = $('#productos_ingreso_table').DataTable($.extend({
            columns: [
                {data: 'id', title: 'Id', className: "text-center"},
                {data: 'btnEliminar', title: 'Eliminar', className: "text-center", searchable: false,},
                {data: 'nombre', title: 'Nombre', className: "text-center font-weight-bold"},
                {data: 'categoria', title: 'Categoría', className: "text-center"},
                {data: 'tipo.nombre', title: 'Tipo', className: "text-center"},
                {
                    data: 'cantidad',
                    title: 'Cantidad (Un/Kg)',
                    className: "text-center",
                    render: function (data, type, row, meta) {
                        return renderChange(data, type, row, meta);
                    }
                }
            ],
            responsive: true
        }, options));

        $('#recurso tbody').on('click', 'tr', function () {
            cargarIngreso(this);
        });

        $('#productos_ingreso_table tbody').on('keyup change', '.child input, .child select, .child textarea', function (e) {
            let $el = $(this);
            let rowIdx = $el.closest('ul').data('dtr-index');
            let colIdx = $el.closest('li').data('dtr-index');
            let cell = productos_ingreso_table.cell({row: rowIdx, column: colIdx}).node();
            $('input, select, textarea', cell).val($el.val());
            if ($el.is(':checked')) {
                $('input', cell).prop('checked', true);
            } else {
                $('input', cell).removeProp('checked');
            }
        });

        $(document).on('click', '[name="btn_eliminar_productos_tabla_ingreso"]', function () {
            let row = $(this).closest('tr');
            quitarProductoIngreso(row)
        });

        $(document).on('click', '[name="btn_agregar_productos_tabla"]', function () {
            let row = productos_table.row($(this).closest('tr'));
            let data = productos_table.row(row).data();
            let newRow = crearFilaTablaIngreso(data);
            if (!isFilaEnTablaIngreso(newRow)) {
                productos_ingreso_table.row.add(newRow).draw();
                productos_ingreso_table.responsive.rebuild();
                productos_ingreso_table.responsive.recalc();
                darFormatoNumerico();
            } else {
                toastr.warning("El producto seleccionado ya se agregó al ingreso");
            }
        });

        $("#registrar").click(function () {
            $.post('/api/ingresos/crear',
                {
                    valor: $('#valor').cleanVal(),
                    productos_ingreso: crearEstructuraDeProductos()
                }, function (data) {
                    swal("¡Operación exitosa!", data.msg, "success");
                    limpiarFormulario();
                    table.ajax.reload();
                }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    swal("Ha ocurrido un error", error[0], "error");
                });
                console.error(err);
            })
        });

        $("#limpiar").click(function () {
            location.href = "{{route('ingresos')}}";
        });

        $("#eliminar").click(function () {
            swal({
                title: "¿Estas seguro?",
                text: "¡Una vez hecho no será posible restablecerlo!",
                icon: "warning",
                dangerMode: true,
                buttons: ["Cancelar", "Borrar"]
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.post('/api/ingresos/anular', {
                            id: $('#id').val()
                        }, function (data) {
                            swal("¡Operación exitosa!", data.msg, "success");
                            limpiarFormulario()
                            table.ajax.reload()
                        }).fail(function (err) {
                            swal("Ha ocurrido un error", "No se puede borrar el recurso, es posible que otra entidad del negocio esté haciendo referencia a este.", "error");
                            console.error(err);
                        })
                    }
                });
        });
    });
</script>
