$(document).ready(function () {
    let btnAgregar = "<input name='btn_agregar_productos_tabla' type='button' value='Agregar' class='btn btn-success container-fluid'/>";

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

    $.ajax({
        url: "api/listarentradas",
        type: "get",
        success: function (data) {
            console.log(data);
        },
        error: function (err) {
            console.warn(err);
        }
    })

    let table = $('#recurso').DataTable($.extend({
        serverSide: true,
        ajax: "api/listarentradas",
        columns: [
            {data: 'id', title: 'Id'},
            {data: 'proveedor.id', title: 'Id del proveedor', visible: false, searchable: false},
            {data: 'proveedor.nombre', title: 'Nombre del proveedor'},
            {data: 'empleado.name', title: 'Nombre del empleado'},
            {data: 'fechapago', title: 'Fecha de pago'},
            {data: 'fechapagado', title: 'Fecha límite de pago'},
            {data: 'costo', title: 'Costo', render: $.fn.dataTable.render.number(',', '.', 0, '$ ')},
            {data: 'created_at', title: 'Fecha de creación'},
            {data: 'updated_at', title: 'Fecha de actualización'},
        ]
    }, options));

    let productos_table = $('#productos_table').DataTable($.extend({
        serverSide: true,
        ajax: 'api/listarproductos',
        columns: [
            {data: 'id', title: 'Id', className: "text-center"},
            {
                data: null, title: 'Agregar', className: "text-center", render: function () {
                    return btnAgregar;
                }
            },
            {data: 'nombre', title: 'Nombre', className: "text-center"},
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
            {data: 'tipo.id', title: 'Id de tipo', visible: false, searchable: false, className: "text-center"},
            {data: 'tipo.nombre', title: 'Tipo', className: "text-center"},
            {data: 'created_at', title: 'Fecha de creación', className: "text-center"},
            {data: 'updated_at', title: 'Fecha de actualización', className: "text-center"},
        ],
        responsive: true
    }, options));

    let tablaProveedores = $('#proveedores').DataTable($.extend({
        serverSide: true,
        ajax: 'api/listarproveedores',
        columns: [
            {data: 'id', title: 'Id', className: "text-center"},
            {data: 'nombre', title: 'Nombre del proveedor', className: "text-center"},
            {data: 'telefono', title: 'Teléfono del proveedor', className: "text-center"},
            {data: 'direccion', title: 'Dirección del empleado', className: "text-center"},
            {data: 'created_at', title: 'Fecha de creación', className: "text-center"},
            {data: 'updated_at', title: 'Fecha de actualización', className: "text-center"},
        ],
        responsive: true
    }, options));

    let productos_entrada_table = $('#productos_entrada_table').DataTable($.extend({
        columns: [
            {data: 'id', title: 'Id', className: "text-center"},
            {data: 'btnEliminar', title: 'Eliminar', className: "text-center"},
            {data: 'nombre', title: 'Nombre', className: "text-center text-wrap"},
            {data: 'categoria', title: 'Categoría', className: "text-center"},
            {data: 'tipo.nombre', title: 'Tipo', className: "text-center"},
            {
                data: 'cantidad',
                title: 'Cantidad (Unidades/Kg)',
                className: "text-center",
                render: function (data, type, row, meta) {
                    return renderChange(data, type, row, meta);
                }
            },
            {
                data: 'costoTotal',
                title: 'Costo total',
                className: "text-center",
                render: function (data, type, row, meta) {
                    return renderChange(data, type, row, meta);
                }
            }
        ],
        responsive: true
    }, options));

    $('#recurso tbody').on('click', 'tr', function () {
        document.getElementById('registrar').disabled = true;
        let data = table.row(this).data();
        document.getElementById('id').value = data['id'];
        document.getElementById('proveedor_id').value = data['proveedor']['id'];
        document.getElementById('fechapago').value = data['fechapago'];
        document.getElementById('fechapagado').value = data['fechapagado'];
        document.getElementById('costo').value = data['costo'];

    });

    $('#proveedores tbody').on('click', 'tr', function () {
        let data = tablaProveedores.row(this).data();
        document.getElementById('proveedor_id').value = data['id'];
    });

    $('#productos_entrada_table tbody').on('keyup change', '.child input, .child select, .child textarea', function (e) {
        let $el = $(this);
        let rowIdx = $el.closest('ul').data('dtr-index');
        let colIdx = $el.closest('li').data('dtr-index');
        let cell = productos_entrada_table.cell({row: rowIdx, column: colIdx}).node();
        $('input, select, textarea', cell).val($el.val());
        if ($el.is(':checked')) {
            $('input', cell).prop('checked', true);
        } else {
            $('input', cell).removeProp('checked');
        }
    });

    $(document).on('click', '[name="btn_eliminar_productos_entrada_table"]', function () {
        let row = $(this).closest('tr');
        quitarProductoDeEntrada(row)
    });

    function vacearTablaDeProductosEntrada() {
        productos_entrada_table.clear().draw();
    }

    function quitarProductoDeEntrada(row) {
        productos_entrada_table.row(row).remove().draw();
    }

    $(document).on('click', '[name="btn_agregar_productos_tabla"]', function () {
        let row = productos_table.row($(this).closest('tr'));
        let data = productos_table.row(row).data();
        let tipoDeCantidad = "";
        if (data['categoria'] == "Unitario") {
            tipoDeCantidad = "Cantidad en unidades";
        } else {
            tipoDeCantidad = "Cantidad en kilos";
        }
        let newRow = $.extend({
            'btnEliminar': "<input name='btn_eliminar_productos_entrada_table' type='button' value='Eliminar' class='btn btn-warning container-fluid'/>",
            'cantidad': "<div class='form-group mb-1'><input id='cantidad_producto_entrada" + data['id'] + "' class='form-control' type='number' placeholder='" + tipoDeCantidad + "'/></div>",
            'costoTotal': "<div class='form-group mb-1'><input id='precio_producto_entrada" + data['id'] + "' class='form-control' type='number' placeholder='Costo total'/></div>"
        }, data)
        if (!productos_entrada_table.row(newRow).any()) {
            productos_entrada_table.row.add(newRow).draw();
            productos_entrada_table.responsive.rebuild();
            productos_entrada_table.responsive.recalc();
        } else {
            toastr.warning("El producto seleccionado ya se agregó a la entrada");
        }
    });

    $(document).on('keyup', '[id^="precio_producto_entrada"]', function () {
        let alreadyUsed = {};
        let costo = 0;
        $('[id^="precio_producto_entrada"]').each(function (index, value) {
            if (!alreadyUsed[$(this).attr("id")]) {
                costo += isNaN(parseInt(value.value, 10)) ? 0 : parseInt(value.value, 10)
            }
            alreadyUsed[$(this).attr("id")] = true;
        })
        document.getElementById("costo").value = costo;
    });

    function crearEstructuraDeProductos() {
        let arr = [];
        productos_entrada_table.rows().every(function (rowIdx, tableLoop, rowLoop) {
            let data = this.data();
            let id = data['id'];
            arr.push({
                id: id,
                cantidad: $('#cantidad_producto_entrada' + id).val(),
                costo: $('#precio_producto_entrada' + id).val()
            });
        });
        console.log(arr);
        return arr;
    }

    $("#pagar").click(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post('api/pagarentrada',
            {
                proveedor_id: $("#proveedor_id").val(),
                fechapago: $("#fechapago").val(),
                productos_entrada: crearEstructuraDeProductos()
            }, function (data) {
                swal(data.mensaje, data.descripcion, "success");
                limpiarFormulario()
                table.ajax.reload();
            }).fail(function (err) {
            $.each(err.responseJSON.errors, function (i, error) {
                toastr.error(error[0]);
            });
            console.error(err);
        })
    });


    function limpiarFormulario() {
        document.getElementById('id').value = "";
        document.getElementById('proveedor_id').value = "";
        document.getElementById('fechapagado').value = "";
        document.getElementById('fechapago').value = "";
        document.getElementById('costo').value = "";
        vacearTablaDeProductosEntrada();
        document.getElementById('pagar').disabled = false;
    }

    $("#limpiar").click(function () {
        limpiarFormulario();
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
                    $.post('api/anularentrada', $('#form').serialize(), function (data) {
                        swal("¡Operación exitosa!", data.msg, "success");
                        limpiarFormulario()
                        table.ajax.reload();
                    }).fail(function (err) {
                        $.each(err.responseJSON.errors, function (i, error) {
                            toastr.error(error[0]);
                        });
                        console.error(err);
                    })
                }
            });
    });
});
