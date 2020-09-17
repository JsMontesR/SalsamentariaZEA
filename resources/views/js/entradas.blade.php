<script>
    $(document).ready(function () {
        let btnAgregar = "<input name='btn_agregar_productos_tabla' type='button' value='Agregar' class='btn btn-success container-fluid'/>";

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
            return arr;
        }

        function limpiarFormulario() {
            document.getElementById('id').value = "";
            document.getElementById('proveedor_id').value = "";
            document.getElementById('fechapagado').value = "";
            document.getElementById('fechapago').value = "";
            document.getElementById('valor').value = "";
            $('#recurso tr').removeClass("selected");
            $('#proveedores tr').removeClass("selected");
            vacearTablaDeProductosEntrada();
            $('#productos_container').show();
            document.getElementById('verpagos').disabled = true;
            document.getElementById('registrar').disabled = false;
            document.getElementById('eliminar').disabled = true;
            document.getElementById('modificar').disabled = true;
            productos_table.ajax.reload()
            tablaProveedores.ajax.reload()
        }

        function vacearTablaDeProductosEntrada() {
            productos_entrada_table.clear().draw();
        }

        function quitarProductoDeEntrada(row) {
            productos_entrada_table.row(row).remove().draw();
        }

        function isFilaEnEntrada(newRow) {
            let data = productos_entrada_table.rows().data();
            let existe = false;
            data.each(function (value) {
                if (value["id"] == newRow["id"]) {
                    existe = true;
                    return false;
                }
            });
            return existe;
        }

        function crearFilaDeEntrada(data) {
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
                'btnEliminar': "<input " + activated + " name='btn_eliminar_productos_entrada_table' type='button' value='Eliminar' class='btn btn-warning container-fluid'/>",
                'cantidad': "<div class='input-group mb-1'><div class='input-group-prepend'><span class='input-group-text'>" + emoji + "</span></div><input " + cantidad + " id='cantidad_producto_entrada" + data['id'] + "' class='form-control' type='number' placeholder='" + tipoDeCantidad + "'/></div>",
                'costoTotal': "<div class='input-group mb-1'><div class='input-group-prepend'><span class='input-group-text'>💵</span></div><input " + costo + " id='precio_producto_entrada" + data['id'] + "' class='form-control' type='number' placeholder='Costo total'/></div>"
            }, data)
        }

        function cargarProductosEntrada(productos) {
            for (i in productos) {
                let newRow = crearFilaDeEntrada(productos[i]);
                productos_entrada_table.row.add(newRow).draw();
                productos_entrada_table.responsive.rebuild();
                productos_entrada_table.responsive.recalc();
            }
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

        let proveedorId;

        function cargarEntrada(row) {
            limpiarFormulario();
            let data = table.row(row).data();
            document.getElementById('id').value = data['id'];
            document.getElementById('proveedor_id').value = data['proveedor']['id'];
            document.getElementById('fechapago').value = data['fechapago'];
            document.getElementById('fechapagado').value = data['fechapagado'];
            $('[name="valor"]').val(data['valor']);
            $('[name="saldo"]').val(data['saldo']);
            $('[name="valorpagado"]').val(data['valor'] - data['saldo']);
            $('#productos_container').hide();
            cargarProductosEntrada(data['productos']);
            proveedorId = data['proveedor']['id'];
            tablaProveedores.columns(0).search(data['proveedor']['id']).draw();
            $(row).addClass("selected");
            document.getElementById('registrar').disabled = true;
            document.getElementById('verpagos').disabled = false;
            document.getElementById('eliminar').disabled = false;
            document.getElementById('modificar').disabled = false;
        }

        // $.ajax({
        //     url: "/api/entradas/listar",
        //     type: "get",
        //     success: function (data) {
        //         console.log(data);
        //     },
        //     error: function (err) {
        //         console.warn(err);
        //     }
        // })

        let proveedorSpecific;
        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            processing: true,
            ajax: "/api/entradas/listar",
            columns: [
                {data: 'id', name: 'entradas.id', title: 'Id', className: "text-center font-weight-bold"},
                {
                    data: 'proveedor.id',
                    name: 'proveedor.id',
                    title: 'Id del proveedor',
                    visible: false,
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'proveedor.nombre',
                    name: 'proveedor.nombre',
                    title: 'Nombre del proveedor',
                    className: "text-center",
                    orderable: false
                },
                {
                    data: 'empleado.name',
                    name: 'empleado.name',
                    title: 'Nombre del empleado',
                    className: "text-center",
                    orderable: false
                },
                {
                    data: 'fechapagado',
                    name: 'entradas.fechapagado',
                    title: 'Fecha de pago',
                    className: "text-center",
                    render: function (data) {
                        if (data) {
                            return '<a class="text-success">' + data + '</a>';
                        } else {
                            return '<a class="text-danger">Sin pagar</a>';
                        }
                    }
                },
                {
                    data: 'fechapago',
                    name: 'entradas.fechapago',
                    title: 'Fecha límite de pago',
                    className: "text-center"
                },
                {
                    data: 'saldo',
                    title: 'Saldo por pagar',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'valor',
                    title: 'Valor de la entrada',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'created_at',
                    title: 'Fecha de creación',
                    className: "text-center"
                },
                {
                    data: 'updated_at',
                    title: 'Fecha de actualización',
                    className: "text-center"
                },
            ],
            order: [[0, 'desc']]
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

        let tablaProveedores = $('#proveedores').DataTable($.extend({
            serverSide: true,
            ajax: '/api/proveedores/listar',
            columns: [
                {data: 'id', title: 'Id', className: "text-center"},
                {data: 'nombre', title: 'Nombre del proveedor', className: "text-center"},
                {data: 'telefono', title: 'Teléfono del proveedor', className: "text-center"},
                {data: 'direccion', title: 'Dirección del empleado', className: "text-center"},
                {data: 'created_at', title: 'Fecha de creación', className: "text-center"},
                {data: 'updated_at', title: 'Fecha de actualización', className: "text-center"},
            ],
            responsive: true,
            drawCallback: function () {
                if (proveedorId) {
                    proveedorSpecific = tablaProveedores.row({search: 'applied'});
                    let foundId = proveedorSpecific.data().id;
                    if (foundId == proveedorId) {
                        cargarProveedor(proveedorSpecific.node());
                    }
                    proveedorId = null;
                }
            }
        }, options));

        let productos_entrada_table = $('#productos_entrada_table').DataTable($.extend({
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
            cargarEntrada(this);
        });

        $('#proveedores tbody').on('click', 'tr', function () {
            cargarProveedor(this)
        });

        function cargarProveedor(row) {
            let data = tablaProveedores.row(row).data();
            $('#proveedores tr').removeClass("selected");
            $(row).addClass("selected");
            document.getElementById('proveedor_id').value = data['id'];
        }

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

        $(document).on('click', '[name="btn_agregar_productos_tabla"]', function () {
            let row = productos_table.row($(this).closest('tr'));
            let data = productos_table.row(row).data();
            let newRow = crearFilaDeEntrada(data);
            if (!isFilaEnEntrada(newRow)) {
                productos_entrada_table.row.add(newRow).draw();
                productos_entrada_table.responsive.rebuild();
                productos_entrada_table.responsive.recalc();
            } else {
                toastr.warning("El producto seleccionado ya se agregó a la entrada");
            }
        });

        $(document).on('keyup', '[id^="precio_producto_entrada"]', function () {
            let alreadyUsed = {};
            let valor = 0;
            $('[id^="precio_producto_entrada"]').each(function (index, value) {
                if (!alreadyUsed[$(this).attr("id")]) {
                    valor += isNaN(parseInt(value.value, 10)) ? 0 : parseInt(value.value, 10)
                }
                alreadyUsed[$(this).attr("id")] = true;
            })
            document.getElementById("valor").value = valor;
        });

        $("#registrar").click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post('/api/entradas/crear',
                {
                    proveedor_id: $("#proveedor_id").val(),
                    fechapago: $("#fechapago").val(),
                    productos_entrada: crearEstructuraDeProductos()
                }, function (data) {
                    swal("¡Operación exitosa!", data.msg, "success");
                    limpiarFormulario();
                    table.ajax.reload();
                }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    toastr.error(error[0]);
                });
                console.error(err);
            })
        });

        $("#limpiar").click(function () {
            location.href = "{{route('entradas')}}";
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
                        $.post('/api/entradas/anular', $('#form').serialize(), function (data) {
                            swal("¡Operación exitosa!", data.msg, "success");
                            limpiarFormulario()
                            table.ajax.reload()
                        }).fail(function (err) {
                            $.each(err.responseJSON.errors, function (i, error) {
                                toastr.error(error[0]);
                            });
                            console.error(err);
                        })
                    }
                });
        });

        $("#modificar").click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post('/api/entradas/modificar',
                {
                    id: $("#id").val(),
                    fechapago: $("#fechapago").val(),
                }, function (data) {
                    table.ajax.reload();
                    limpiarFormulario();
                    swal("¡Operación exitosa!", data.msg, "success");
                }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    toastr.error(error[0]);
                });
                console.error(err);
            })
        })

        /*
            SECCION MODAL
         */

        let pagos_table;

        $("#verpagos").click(function () {
            $.ajax({
                url: "/api/entradas/" + $("#id").val() + "/pagos",
                type: "get",
                success: function (data) {
                    console.log(data);
                },
                error: function (err) {
                    console.warn(err);
                }
            })
            if ($.fn.DataTable.isDataTable('#pagos_table')) {
                pagos_table.destroy();
            }
            pagos_table = $('#pagos_table').DataTable($.extend({
                serverSide: true,
                ajax: '/api/entradas/' + $("#id").val() + '/pagos',
                columns: [
                    {data: 'id', title: 'Id', className: "text-center"},
                    {data: 'empleado.name', title: 'Nombre del empleado', className: "text-center"},
                    {
                        data: 'parteEfectiva',
                        title: 'Parte efectiva',
                        className: "text-center",
                        render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                    },
                    {
                        data: 'parteCrediticia',
                        title: 'Parte crediticia',
                        className: "text-center",
                        render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                    },
                    {data: 'created_at', title: 'Fecha de creación', className: "text-center"}
                ],
                responsive: true
            }, options));
        })

        $("#pagar").click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post('/api/entradas/pagar',
                {
                    id: $("#id").val(),
                    parteCrediticia: $("#parteCrediticia").val(),
                    parteEfectiva: $("#parteEfectiva").val()
                }, function (data) {
                    table.ajax.reload();
                    limpiarFormularioModal()
                    limpiarFormulario();
                    swal("¡Operación exitosa!", data.msg, "success");
                }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    toastr.error(error[0]);
                });
                console.error(err);
            })
        })

        $("#anularpago").click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post('/api/movimientos/anularPago',
                {
                    id: $("#idpago").val(),
                }, function (data) {
                    pagos_table.ajax.reload();
                    table.ajax.reload();
                    limpiarFormularioModal();
                    limpiarFormulario();
                    swal("¡Operación exitosa!", data.msg, "success");
                }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    toastr.error(error[0]);
                });
                console.error(err);
            })
        })

        $("#limpiarmodal,#cerrarmodal").click(function () {
            limpiarFormularioModal();
        })

        function limpiarFormularioModal() {
            $("#idpago").val("");
            $("#parteEfectiva").val("");
            $("#parteCrediticia").val("");
            $('#pagos_table tr').removeClass("selected");
        }

        $('#pagos_table tbody').on('click', 'tr', function () {
            limpiarFormularioModal();
            $(this).addClass('selected');
            let data = pagos_table.row(this).data();
            $("#idpago").val(data["id"]);
            $("#parteEfectiva").val(data["parteEfectiva"]);
            $("#parteCrediticia").val(data["parteCrediticia"]);
        });
    });
</script>
