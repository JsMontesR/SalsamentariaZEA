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
            productos_entrada_table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                let data = this.data();
                let id = data['id'];
                arr.push({
                    id: id,
                    cantidad: $('#cantidad_producto_entrada' + id).cleanVal(),
                    costo: $('#precio_producto_entrada' + id).cleanVal(),
                    unidad: $('#unidades' + id).val()
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
            document.getElementById('imprimir').disabled = true;
            document.getElementById('registrar').disabled = false;
            document.getElementById('registrarypagar').disabled = false;
            document.getElementById('otherregister').disabled = false;
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
                cantidad = "readonly value=" + data['pivot']['cantidad']
                costo = "readonly value=" + data['pivot']['costo'];
                activated = "disabled";
                gadget = gadget.replace("select", "select disabled");
                if (data['categoria'] == "Granel") {
                    gadget = gadget.replace(data['pivot']['unidad'] + '"', data['pivot']['unidad'] + '" selected');
                }
            }

            return $.extend({
                'btnEliminar': "<input " + activated + " name='btn_eliminar_productos_entrada_table' type='button' value='Eliminar' class='btn btn-warning container-fluid'/>",
                'cantidad': "<div class='input-group mb-1'><div class='input-group-prepend'><span class='input-group-text'>" + gadget + "</span></div><input " + cantidad + " id='cantidad_producto_entrada" + data['id'] + "' class='money form-control' type='number' placeholder='" + tipoDeCantidad + "'/></div>",
                'costoTotal': "<div class='input-group mb-1'><div class='input-group-prepend'><span class='input-group-text'>💵</span></div><input " + costo + " id='precio_producto_entrada" + data['id'] + "' class='money form-control' type='number' placeholder='Costo total'/></div>"
            }, data)
        }

        function cargarProductosEntrada(productos) {
            for (i in productos) {
                let newRow = crearFilaDeEntrada(productos[i]);
                productos_entrada_table.row.add(newRow).draw();
                productos_entrada_table.responsive.rebuild();
                productos_entrada_table.responsive.recalc();
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

        let proveedorId;

        function cargarEntrada(row, data = null) {
            limpiarFormulario();
            if (data == null) {
                data = table.row(row).data();
            }
            $(row).addClass("selected");
            document.getElementById('id').value = data['id'];
            document.getElementById('proveedor_id').value = data['proveedor']['id'];
            document.getElementById('fechapago').value = data['fechapago'];
            document.getElementById('fechapagado').value = data['fechapagado'];
            $('[name="valor"]').val(data['valor']).trigger('input');
            $('[name="saldo"]').val(data['saldo']).trigger('input');
            $('[name="valorpagado"]').val(data['valor'] - data['saldo']).trigger('input');
            $('#productos_container').hide();
            cargarProductosEntrada(data['productos']);
            proveedorId = data['proveedor']['id'];
            tablaProveedores.columns(0).search(data['proveedor']['id']).draw();
            document.getElementById('registrar').disabled = true;
            document.getElementById('registrarypagar').disabled = true;
            document.getElementById('otherregister').disabled = true;
            document.getElementById('verpagos').disabled = false;
            document.getElementById('imprimir').disabled = false;
            document.getElementById('eliminar').disabled = false;
            document.getElementById('modificar').disabled = false;
        }

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
                darFormatoNumerico();
            } else {
                toastr.warning("El producto seleccionado ya se agregó a la entrada");
            }
        });

        $(document).on('keyup', '[id^="precio_producto_entrada"]', function () {
            calcularTotal();
        });

        function calcularTotal() {
            let alreadyUsed = {};
            let valor = 0;
            $('[id^="precio_producto_entrada"]').each(function (index, value) {
                if (!alreadyUsed[$(this).attr("id")]) {
                    let val = $(this).cleanVal();
                    valor += isNaN(parseInt(val, 10)) ? 0 : parseInt(val, 10)
                }
                alreadyUsed[$(this).attr("id")] = true;
            })
            $("#valor").val(valor).trigger('input');
        }

        $(document).on('keyup change', '[id^="cantidad_producto_entrada"]', function () {
            darFormatoNumerico();
            let cantidad = $(this).cleanVal();
            let precio = parseInt($(this).attr("precio"));
            let idPrecio = $(this).attr("id").replace("cantidad", "precio");
            let unidades = $("#unidades" + $(this).attr("id").replace("cantidad_producto_entrada", "")).val();
            if (unidades == "gramos") {
                precio = precio / 1000;
            }
            let total = isNaN(parseInt(cantidad * precio, 10)) ? 0 : parseInt(cantidad * precio, 10)
            $("[name='" + idPrecio + "']").val(total).trigger('input');
            calcularTotal();
        });

        $("#registrar").click(function () {
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
                    swal("Ha ocurrido un error", error[0], "error");
                });
                console.error(err);
            })
        });

        $("#registrarypagar").click(function () {
            $.post('/api/entradas/crear',
                {
                    proveedor_id: $("#proveedor_id").val(),
                    fechapago: $("#fechapago").val(),
                    productos_entrada: crearEstructuraDeProductos()
                }, function (data) {
                    toastr.success(data.msg);
                    $("#modalMovimientos").modal('show');
                    limpiarFormulario();
                    cargarEntrada(null, data.data);
                    cargarModalPagos(data.data.id);
                    table.ajax.reload();
                }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    swal("Ha ocurrido un error", error[0], "error");
                });
                console.error(err);
            })
        });

        $("#imprimir").click(function () {
            $("#form").attr('action', "{{ route('imprimircomprobanteentrada') }}");
            $("#form").submit();
        })

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
                            swal("Ha ocurrido un error", "No se puede borrar el recurso, es posible que otra entidad del negocio esté haciendo referencia a este.", "error");
                            console.error(err);
                        })
                    }
                });
        });

        $("#modificar").click(function () {
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
                    swal("Ha ocurrido un error", error[0], "error");
                });
                console.error(err);
            })
        })

        /*
            SECCION MODAL
         */

        let pagos_table;

        $("#verpagos").click(function () {
            cargarModalPagos();
        })

        function cargarModalPagos(idDeEntrada = null) {
            darFormatoNumerico();
            let id = idDeEntrada == null ? $("#id").val() : idDeEntrada;
            if ($.fn.DataTable.isDataTable('#pagos_table')) {
                pagos_table.destroy();
            }
            pagos_table = $('#pagos_table').DataTable($.extend({
                serverSide: true,
                ajax: '/api/entradas/' + id + '/pagos',
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
                    {data: 'created_at', title: 'Fecha de creación', className: "text-center"}
                ],
                responsive: true
            }, options));
        }

        $("#pagar").click(function () {
            $.post('/api/movimientos/generarPago',
                {
                    id: $("#id").val(),
                    parteCrediticia: $("#parteCrediticia").cleanVal(),
                    parteEfectiva: $("#parteEfectiva").cleanVal(),
                    movimientoable: "entrada"
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

        $("#anularpago").click(function () {
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
                    swal("Ha ocurrido un error", error[0], "error");
                });
                console.error(err);
            })
        })

        $("#limpiarmodal,#cerrarmodal").click(function () {
            limpiarFormularioModal();
        })

        function limpiarFormularioModal() {
            document.getElementById("anularpago").disabled = true;
            document.getElementById("pagar").disabled = false;
            $("#idpago").val("");
            $("#parteEfectiva").val("");
            $("#parteCrediticia").val("");
            $('#pagos_table tr').removeClass("selected");
        }

        $('#pagos_table tbody').on('click', 'tr', function () {
            limpiarFormularioModal();
            $(this).addClass('selected');
            let data = pagos_table.row(this).data();
            document.getElementById("anularpago").disabled = false;
            document.getElementById("pagar").disabled = true;
            $("#idpago").val(data["id"]);
            $("#parteEfectiva").val(data["parteEfectiva"]).trigger('input');
            $("#parteCrediticia").val(data["parteCrediticia"]).trigger('input');
        });
    });
</script>
