<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let btnAgregar = "<input name='btn_agregar_productos_tabla' type='button' value='Agregar' class='btn btn-success container-fluid'/>";

        function darFormatoNumerico() {
            $('.money').mask('#.##0', {reverse: true});
        }

        function crearEstructuraDeProductos() {
            let arr = [];
            productos_carrito_table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                let data = this.data();
                let id = data['id'];
                arr.push({
                    id: id,
                    cantidad: $('#cantidad_producto_carrito' + id).cleanVal(),
                    costo: $('#precio_producto_carrito' + id).cleanVal(),
                    unidad: $('#unidades' + id).val(),
                });
            });
            return arr;
        }

        function limpiarFormulario() {
            document.getElementById('id').value = "";
            document.getElementById('cliente_id').value = "";
            document.getElementById('fechapagado').value = "";
            document.getElementById('fechapago').value = "";
            document.getElementById('lugarentrega').value = "";
            document.getElementById('valor').value = "";
            $('#recurso tr').removeClass("selected");
            $('#clientes tr').removeClass("selected");
            vacearCarrito();
            $('#productos_container').show();
            document.getElementById('vercobros').disabled = true;
            document.getElementById('imprimir').disabled = true;
            document.getElementById('registrar').disabled = false;
            document.getElementById('registrarycobrar').disabled = false;
            document.getElementById('otherregister').disabled = false;
            document.getElementById('eliminar').disabled = true;
            document.getElementById('modificar').disabled = true;
            productos_table.ajax.reload()
            tablaClientes.ajax.reload()
        }

        function vacearCarrito() {
            productos_carrito_table.clear().draw();
        }

        function quitarProductoDelCarrito(row) {
            productos_carrito_table.row(row).remove().draw();
        }

        function isFilaEnCarrito(newRow) {
            let data = productos_carrito_table.rows().data();
            let existe = false;
            data.each(function (value) {
                if (value["id"] == newRow["id"]) {
                    existe = true;
                    return false;
                }
            });
            return existe;
        }

        function crearFilaDeCarrito(data) {
            let tipoDeCantidad = "";
            let gadget = "";
            let activated = "";
            let cantidad = "";
            let costo = "";
            let unidad = "";

            if (data['categoria'] == "Unitario") {
                tipoDeCantidad = "# de unidades";
                gadget = "📦";
            } else {
                tipoDeCantidad = "";
                gadget = '<select onchange="" ' + unidad + ' id= "unidades' + data['id'] + '"><option value="gramos">g</option><option value="kilogramos">Kg</option></select>';
            }

            if (data['pivot'] != undefined) {
                cantidad = "readonly value=" + data['pivot']['cantidad'];
                costo = "readonly value=" + data['pivot']['costo'];
                activated = "disabled";
                gadget = gadget.replace("select", "select disabled");
                if (data['categoria'] == "Granel") {
                    gadget = gadget.replace(data['pivot']['unidad'] + '"', data['pivot']['unidad'] + '" selected');
                }
            }

            return $.extend({
                'btnEliminar': "<input " + activated + " name='btn_eliminar_productos_carrito' type='button' value='Eliminar' class='btn btn-warning container-fluid'/>",
                'cantidad': "<div class='input-group mb-1'><div class='input-group-prepend'><span class='input-group-text'>" +
                    gadget + "</span></div><input " + cantidad + " id='cantidad_producto_carrito" + data['id'] +
                    "' precio=" + data['precio'] + " class='money form-control' type='text' placeholder='" + tipoDeCantidad +
                    "'/></div>",
                'costoTotal': "<div class='input-group mb-1'><div class='input-group-prepend'><span class='input-group-text'>💵</span></div><input " + costo
                    + " id='precio_producto_carrito" + data['id'] + "' " + " name='precio_producto_carrito" + data['id'] +
                    "' class='money form-control' type='text' placeholder='Costo total'/></div>"
            }, data)
        }

        function cargarProductosVenta(productos) {
            for (i in productos) {
                let newRow = crearFilaDeCarrito(productos[i]);
                productos_carrito_table.row.add(newRow).draw();
                productos_carrito_table.responsive.rebuild();
                productos_carrito_table.responsive.recalc();
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

        let clienteId;

        function cargarVenta(row, data = null) {
            limpiarFormulario();
            if (data == null) {
                data = table.row(row).data();
            }
            $(row).addClass("selected");
            document.getElementById('id').value = data['id'];
            document.getElementById('cliente_id').value = data['cliente']['id'];
            document.getElementById('fechapago').value = data['fechapago'];
            document.getElementById('lugarentrega').value = data['lugarentrega'];
            document.getElementById('fechapagado').value = data['fechapagado'];
            $('[name="valor"]').val(data['valor']).trigger('input');
            $('[name="saldo"]').val(data['saldo']).trigger('input');
            $('[name="valorcobrado"]').val(data['valor'] - data['saldo']).trigger('input');
            $('#productos_container').hide();
            cargarProductosVenta(data['productos']);
            clienteId = data['cliente']['id'];
            tablaClientes.columns(0).search(data['cliente']['id']).draw();
            document.getElementById('registrar').disabled = true;
            document.getElementById('registrarycobrar').disabled = true;
            document.getElementById('otherregister').disabled = true;
            document.getElementById('vercobros').disabled = false;
            document.getElementById('imprimir').disabled = false;
            document.getElementById('eliminar').disabled = false;
            document.getElementById('modificar').disabled = false;
        }

        let clienteSpecific;
        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            processing: true,
            ajax: "/api/ventas/listar",
            columns: [
                {data: 'id', name: 'ventas.id', title: 'Id', className: "text-center font-weight-bold"},
                {
                    data: 'cliente.id',
                    name: 'cliente.id',
                    title: 'Id del cliente',
                    visible: false,
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'cliente.name',
                    name: 'cliente.name',
                    title: 'Nombre del cliente',
                    className: "text-center",
                },
                {
                    data: 'empleado.name',
                    name: 'empleado.name',
                    title: 'Nombre del vendedor',
                    className: "text-center",
                },
                {
                    data: 'fechapagado',
                    name: 'ventas.fechapagado',
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
                    name: 'ventas.fechapago',
                    title: 'Fecha límite de pago',
                    className: "text-center",
                    render: function (data) {
                        if (data) {
                            return '<a>' + data + '</a>';
                        } else {
                            return '<a class="text-warning">Sin fecha límite de pago</a>';
                        }
                    }
                },
                {
                    data: 'saldo',
                    title: 'Saldo por cobrar',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'valor',
                    title: 'Valor de la venta',
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
            ]
        }, options));

        $('#recurso thead th').each(function () {
            var title = $(this).text();
            let id = "";
            if (title == "Id") {
                id = 'id = "specific"'
            }
            $(this).html(title + ' <input ' + id + 'type="text" class="col-search-input container-fluid" placeholder="Buscar" />');
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

        let tablaClientes = $('#clientes').DataTable($.extend({
            serverSide: true,
            ajax: '/api/clientes/listar',
            columns: [
                {data: 'id', title: 'Id', className: "text-center"},
                {data: 'name', title: 'Nombre', className: "text-center"},
                {
                    data: 'di',
                    title: 'Documento de identidad',
                    render: $.fn.dataTable.render.number('.', '.', 0),
                    className: "text-center"
                },
                {data: 'celular', title: 'Teléfono celular', className: "text-center"},
                {data: 'fijo', title: 'Teléfono fijo', className: "text-center"},
                {data: 'email', title: 'Correo electrónico', className: "text-center"},
                {data: 'direccion', title: 'Dirección', className: "text-center"},
                {data: 'created_at', title: 'Fecha de creación', className: "text-center"},
                {data: 'updated_at', title: 'Fecha de actualización', className: "text-center"},
            ],
            responsive: true,
            drawCallback: function () {
                if (clienteId) {
                    clienteSpecific = tablaClientes.row({search: 'applied'});
                    let foundId = clienteSpecific.data().id;
                    if (foundId == clienteId) {
                        cargarCliente(clienteSpecific.node());
                    }
                    clienteId = null;
                }
            }
        }, options));

        let productos_carrito_table = $('#productos_carrito_table').DataTable($.extend({
            columns: [
                {data: 'id', title: 'Id', className: "text-center"},
                {data: 'btnEliminar', title: 'Eliminar', className: "text-center", searchable: false,},
                {data: 'nombre', title: 'Nombre', className: "text-center font-weight-bold"},
                {data: 'categoria', title: 'Categoría', className: "text-center"},
                {data: 'tipo.nombre', title: 'Tipo', className: "text-center"},
                {
                    data: 'cantidad',
                    title: 'Cantidad',
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
            cargarVenta(this);
        });

        $('#clientes tbody').on('click', 'tr', function () {
            cargarCliente(this)
        });

        function cargarCliente(row) {
            let data = tablaClientes.row(row).data();
            $('#clientes tr').removeClass("selected");
            $(row).addClass("selected");
            document.getElementById('cliente_id').value = data['id'];
        }

        $('#productos_carrito_table tbody').on('keyup change', '.child input, .child select, .child textarea', function (e) {
            let $el = $(this);
            let rowIdx = $el.closest('ul').data('dtr-index');
            let colIdx = $el.closest('li').data('dtr-index');
            let cell = productos_carrito_table.cell({row: rowIdx, column: colIdx}).node();
            $('input, select, textarea', cell).val($el.val());
            if ($el.is(':checked')) {
                $('input', cell).prop('checked', true);
            } else {
                $('input', cell).removeProp('checked');
            }
        });

        $(document).on('click', '[name="btn_eliminar_productos_carrito"]', function () {
            let row = $(this).closest('tr');
            quitarProductoDelCarrito(row)
        });

        $(document).on('click', '[name="btn_agregar_productos_tabla"]', function () {
            let row = productos_table.row($(this).closest('tr'));
            let data = productos_table.row(row).data();
            let newRow = crearFilaDeCarrito(data);
            if (!isFilaEnCarrito(newRow)) {
                productos_carrito_table.row.add(newRow).draw();
                productos_carrito_table.responsive.rebuild();
                productos_carrito_table.responsive.recalc();
                darFormatoNumerico();
            } else {
                toastr.warning("El producto seleccionado ya se agregó a la venta");
            }
        });

        $(document).on('keyup', '[id^="precio_producto_carrito"]', function () {
            calcularTotal();
        });

        function calcularTotal() {
            let alreadyUsed = {};
            let valor = 0;
            $('[id^="precio_producto_carrito"]').each(function (index, value) {
                if (!alreadyUsed[$(this).attr("id")]) {
                    let val = $(this).cleanVal();
                    valor += isNaN(parseInt(val, 10)) ? 0 : parseInt(val, 10)
                }
                alreadyUsed[$(this).attr("id")] = true;
            })
            $("#valor").val(valor).trigger('input');
        }

        $(document).on('keyup change', '[id^="cantidad_producto_carrito"]', function () {
            darFormatoNumerico();
            let cantidad = $(this).cleanVal();
            let precio = parseInt($(this).attr("precio"));
            let idPrecio = $(this).attr("id").replace("cantidad", "precio");
            let unidades = $("#unidades" + $(this).attr("id").replace("cantidad_producto_carrito", "")).val();
            if (unidades == "gramos") {
                precio = precio / 1000;
            }
            let total = isNaN(parseInt(cantidad * precio, 10)) ? 0 : parseInt(cantidad * precio, 10);
            $("[name='" + idPrecio + "']").val(total).trigger('input');
            calcularTotal();
        });

        $(document).on('change', '[id^="unidades"]', function () {
            darFormatoNumerico();
            let id = $(this).attr("id").replace("unidades", "");
            let cantidad = $("#cantidad_producto_carrito" + id).cleanVal();
            let precio = parseInt($("#cantidad_producto_carrito" + id).attr("precio"));
            let idPrecio = "precio_producto_carrito" + id;
            let unidades = $(this).val();
            if (unidades == "gramos") {
                precio = precio / 1000;
            }
            let total = isNaN(parseInt(cantidad * precio, 10)) ? 0 : parseInt(cantidad * precio, 10)
            $("[name='" + idPrecio + "']").val(total).trigger('input');
            calcularTotal();
        });

        $("#registrar").click(function () {
            $.post('/api/ventas/crear',
                {
                    cliente_id: $("#cliente_id").val(),
                    fechapago: $("#fechapago").val(),
                    lugarentrega: $("#lugarentrega").val(),
                    productos_venta: crearEstructuraDeProductos()
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

        $("#registrarycobrar").click(function () {
            $.post('/api/ventas/crear',
                {
                    cliente_id: $("#cliente_id").val(),
                    fechapago: $("#fechapago").val(),
                    lugarentrega: $("#lugarentrega").val(),
                    productos_venta: crearEstructuraDeProductos()
                }, function (data) {
                    toastr.success(data.msg);
                    $("#modalMovimientos").modal('show');
                    limpiarFormulario();
                    cargarVenta(null, data.data);
                    cargarModalCobros(data.data.id);
                    table.ajax.reload();
                }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    swal("Ha ocurrido un error", error[0], "error");
                });
                console.error(err);
            })
        });

        $("#imprimir").click(function () {
            $("#form").attr('action', "{{ route('imprimirfactura') }}");
            $("#form").submit();
        })

        $("#limpiar").click(function () {
            location.href = "{{route('ventas')}}";
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
                        $.post('/api/ventas/anular', $('#form').serialize(), function (data) {
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

        $("#modificar").click(function () {
            $.post('/api/ventas/modificar',
                {
                    id: $("#id").val(),
                    fechapago: $("#fechapago").val(),
                    lugarentrega: $("#lugarentrega").val()
                }, function (data) {
                    table.ajax.reload();
                    limpiarFormulario();
                    swal("¡Operación exitosa!", data.msg, "success");
                }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    swal("Ha ocurrido un error", error[0], "error");
                });
                console.error(err);
            })
        })

        /*
            SECCION MODAL
         */

        let cobros_table;

        $("#vercobros").click(function () {
            cargarModalCobros();
        })

        function cargarModalCobros(idDeVenta = null) {
            darFormatoNumerico();
            let id = idDeVenta == null ? $("#id").val() : idDeVenta;
            if ($.fn.DataTable.isDataTable('#cobros_table')) {
                cobros_table.destroy();
            }
            cobros_table = $('#cobros_table').DataTable($.extend({
                serverSide: true,
                ajax: '/api/ventas/' + id + '/cobros',
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
                    {
                        data: 'total',
                        title: 'Total de dinero',
                        className: "text-center",
                        render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                    },
                    {
                        data: 'efectivoRecibido',
                        title: 'Efectivo recibido',
                        className: "text-center",
                        render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                    },
                    {
                        data: 'cambio',
                        title: 'Cambio',
                        className: "text-center",
                        render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                    },
                    {data: 'created_at', title: 'Fecha de creación', className: "text-center"}
                ],
                responsive: true
            }, options));
        }

        $("#cobrar").click(function () {
            $.post('/api/movimientos/generarCobro',
                {
                    id: $("#id").val(),
                    parteCrediticia: $("#parteCrediticia").cleanVal(),
                    parteEfectiva: $("#parteEfectiva").cleanVal(),
                    efectivoRecibido: $("#efectivoRecibido").cleanVal(),
                    movimientoable: "venta"
                }, function (data) {
                    table.ajax.reload();
                    limpiarFormularioModal()
                    limpiarFormulario();
                    swal("¡Operación exitosa!", data.msg, "success");
                }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    swal("Ha ocurrido un error", error[0], "error");
                });
                console.error(err);
            })
        })

        $("#cobrareimprimir").click(function () {
            $.post('/api/movimientos/generarCobro',
                {
                    id: $("#id").val(),
                    parteCrediticia: $("#parteCrediticia").cleanVal(),
                    parteEfectiva: $("#parteEfectiva").cleanVal(),
                    efectivoRecibido: $("#efectivoRecibido").cleanVal(),
                    movimientoable: "venta"
                }, function (data) {
                    table.ajax.reload();
                    swal("¡Operación exitosa!", data.msg, "success");
                    $("#idcobro").val(data.data.id);
                    $("#formcobro").attr('action', "{{ route('imprimirpos') }}");
                    $("#formcobro").submit();
                }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    swal("Ha ocurrido un error", error[0], "error");
                });
                console.error(err);
            })
        })

        $("#imprimirpos").click(function () {
            $("#formcobro").attr('action', "{{ route('imprimirpos') }}");
            $("#formcobro").submit();
        })

        $("#anularcobro").click(function () {
            $.post('/api/movimientos/anularCobro',
                {
                    id: $("#idcobro").val(),
                }, function (data) {
                    cobros_table.ajax.reload();
                    table.ajax.reload();
                    limpiarFormularioModal();
                    limpiarFormulario();
                    swal("¡Operación exitosa!", data.msg, "success");
                }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    swal("Ha ocurrido un error", error[0], "error");
                });
                console.error(err);
            })
        })

        $("#limpiarmodal,#cerrarmodal").click(function () {
            limpiarFormularioModal();
        })

        function limpiarFormularioModal() {
            document.getElementById("imprimirpos").disabled = true;
            document.getElementById("anularcobro").disabled = true;
            document.getElementById("cobrar").disabled = false;
            document.getElementById("otherpay").disabled = false;
            $("#idcobro").val("");
            $("#parteEfectiva").val("");
            $("#efectivoRecibido").val("");
            $("#cambio").val("");
            $("#parteCrediticia").val("");
            $('#cobros_table tr').removeClass("selected");
        }

        $('#cobros_table tbody').on('click', 'tr', function () {
            limpiarFormularioModal();
            $(this).addClass('selected');
            let data = cobros_table.row(this).data();
            document.getElementById("anularcobro").disabled = false;
            document.getElementById("cobrar").disabled = true;
            $("#idcobro").val(data["id"]);
            $("#parteEfectiva").val(data["parteEfectiva"]).trigger('input');
            $("#parteCrediticia").val(data["parteCrediticia"]).trigger('input');
            $("#efectivoRecibido").val(data["efectivoRecibido"]).trigger('input');
            $("#cambio").val(data["cambio"]).trigger('input');
        });

        $('#parteEfectiva,#efectivoRecibido').on('keyup change', function () {
            var parteEfectiva = $("#parteEfectiva").cleanVal();
            var efectivoRecibido = $("#efectivoRecibido").cleanVal();
            try {
                parteEfectiva = parseInt(parteEfectiva, 10);
                efectivoRecibido = parseInt(efectivoRecibido, 10);
                if (efectivoRecibido >= parteEfectiva) {
                    $("#cambio").val(efectivoRecibido - parteEfectiva).trigger('input');
                } else {
                    $("#cambio").val(0).trigger();
                }
            } catch (e) {
            }
        })
    })
    ;
</script>
