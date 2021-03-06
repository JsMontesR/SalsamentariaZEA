<script>
    $(document).ready(function () {

        darFormatoNumerico();

        function darFormatoNumerico() {
            $('.number').mask('#.##0', {reverse: true});
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function limpiarFormulario() {
            document.getElementById('id').value = "";
            document.getElementById('servicio_id').value = "";
            document.getElementById('valorbase').value = "";
            document.getElementById('valortotal').value = "";
            document.getElementById('fechapago').value = "";
            document.getElementById('parteEfectiva').value = "";
            document.getElementById('parteCrediticia').value = "";
            document.getElementById('pagar').disabled = false;
            $("#valortotal").prop("readonly", false);
            $('#recurso tr').removeClass("selected");
            document.getElementById('verpagos').disabled = true;
            document.getElementById('imprimir').disabled = true;
            document.getElementById('registrar').disabled = false;
            document.getElementById('eliminar').disabled = true;
            document.getElementById('modificar').disabled = true;
        }

        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            ajax: 'api/servicios/listar',
            columns: [
                {data: 'id', name: 'servicios.id', title: 'Id', className: "text-center"},
                {
                    data: 'tipo_servicio.id',
                    name: 'tipo_servicios.id',
                    title: 'Id del tipo de servicio',
                    visible: false,
                    searchable: false
                },
                {
                    data: 'tipo_servicio.nombre',
                    name: 'tipo_servicio.nombre',
                    title: 'Nombre del tipo de servicio',
                    className: "text-center"
                },
                {
                    data: 'empleado.name',
                    name: 'empleado.name',
                    title: 'Empleado',
                    className: "text-center",
                },
                {
                    data: 'fechapagado',
                    name: 'servicios.fechapagado',
                    title: 'Fecha de pago',
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ '),
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
                    name: 'servicios.fechapago',
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
                    data: 'abonado',
                    title: 'Valor cobrado',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'saldo',
                    title: 'Saldo por cobrar',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },

                {
                    data: 'valor',
                    name: 'servicios.valor',
                    title: 'Valor',
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ '),
                    className: "text-center"
                },
                {
                    data: 'created_at',
                    name: 'servicios.created_at',
                    title: 'Fecha de creación',
                    className: "text-center"
                },
                {
                    data: 'updated_at',
                    name: 'servicios.updated_at',
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

        let tipoServicioId;

        function cargarServicio(row, data = null) {
            limpiarFormulario();
            if (data == null) {
                data = table.row(row).data();
            }
            $(row).addClass('selected');
            document.getElementById('id').value = data['id'];
            document.getElementById('servicio_id').value = data['tipo_servicio']['id'];
            document.getElementById('nombre').value = data['tipo_servicio']['nombre'];
            document.getElementById('fechapago').value = data['fechapago'];
            $("#valortotal").prop("readonly", true);
            $('#di').val(data['tipo_servicio']['di']).trigger('input');
            $('#salario').val(data['tipo_servicio']['salario']).trigger('input');
            $('[name="valor"]').val(data['valor']).trigger('input');
            $('#valortotal').val(data['valor']).trigger('input');
            $('#parteEfectiva').val(data['saldo']).trigger('input');
            $('[name="saldo"]').val(data['saldo']).trigger('input');
            $('[name="valorpagado"]').val(data['valor'] - data['saldo']).trigger('input');
            tipoServicioId = data['tipo_servicio']['id'];
            tipos_servicios.columns(0).search(tipoServicioId).draw();
            document.getElementById('registrar').disabled = true;
            document.getElementById('verpagos').disabled = false;
            document.getElementById('imprimir').disabled = false;
            document.getElementById('eliminar').disabled = false;
            document.getElementById('modificar').disabled = false;
        }

        $('#recurso tbody').on('click', 'tr', function () {
            cargarServicio(this);
        });

        let tipoServicioSpecific;
        let tipos_servicios = $('#tipo_servicio').DataTable($.extend({
            serverSide: true,
            ajax: 'api/tiposervicios/listar',
            columns: [
                {data: 'id', title: 'Id', className: "text-center"},
                {data: 'nombre', title: 'Nombre', className: "text-center"},
                {
                    data: 'costo',
                    title: 'Costo',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
            ],
            drawCallback: function () {
                if (tipoServicioId) {
                    tipoServicioSpecific = tipos_servicios.row({search: 'applied'});
                    let foundId = tipoServicioSpecific.data().id;
                    if (foundId == tipoServicioId) {
                        cargarTipoServicio(tipoServicioSpecific.node());
                    }
                    tipoServicioId = null;
                }
            }
        }, options));

        $('#tipo_servicio tbody').on('click', 'tr', function () {
            cargarTipoServicio(this);
        });

        function cargarTipoServicio(row) {
            let data = tipos_servicios.row(row).data();
            $('#tipo_servicio tr').removeClass("selected");
            $(row).addClass('selected');
            document.getElementById('servicio_id').value = data['id'];
            document.getElementById('nombre').value = data['nombre'];
            $('#di').val(data['di']).trigger('input');
            $('#valorbase').val(data['costo']).trigger('input');
            $('#valortotal').val(data['costo']).trigger('input');
            $('#parteEfectiva').val(data['salario']).trigger('input');
        }

        $("#registrar").click(function () {
            $.post('/api/servicios/crear',
                {
                    servicio_id: $("#servicio_id").val(),
                    valortotal: $("#valortotal").cleanVal(),
                    fechapago: $("#fechapago").val(),
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
            $.post('/api/servicios/crear',
                {
                    servicio_id: $("#servicio_id").val(),
                    valortotal: $("#valortotal").cleanVal(),
                    fechapago: $("#fechapago").val(),
                }, function (data) {
                    toastr.success(data.msg);
                    $("#modalMovimientos").modal('show');
                    limpiarFormulario();
                    cargarServicio(null, data.data);
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
            $("#form").attr('action', "{{ route('imprimircomprobanteservicio') }}");
            $("#form").submit();
        })

        $("#limpiar").click(function () {
            location.href = "{{route('servicios')}}";
        });

        $("#modificar").click(function () {
            $.post('/api/servicios/modificar',
                {
                    id: $("#id").val(),
                    servicio_id: $("#servicio_id").val(),
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
                        $.post('api/servicios/anular', {
                            id: $('#id').val()
                        }, function (data) {
                            swal("¡Operación exitosa!", data.msg, "success");
                            limpiarFormulario()
                            table.ajax.reload();
                        }).fail(function (err) {
                            swal("Ha ocurrido un error", "No se puede borrar el recurso, es posible que otra entidad del negocio esté haciendo referencia a este.", "error");
                            console.error(err);
                        })
                    }
                });
        });

        /*
        SECCION MODAL
        */
        let pagos_table;

        $("#verpagos").click(function () {
            cargarModalPagos()
        })

        function cargarModalPagos(idDeServicio = null) {
            darFormatoNumerico();
            let id = idDeServicio == null ? $("#id").val() : idDeServicio;
            if ($.fn.DataTable.isDataTable('#pagos_table')) {
                pagos_table.destroy();
            }
            pagos_table = $('#pagos_table').DataTable($.extend({
                serverSide: true,
                ajax: '/api/servicios/' + id + '/pagos',
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
                    {data: 'created_at', title: 'Fecha de realización', className: "text-center"}
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
                    movimientoable: "servicio"
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
            pagos_table.ajax.reload();
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
