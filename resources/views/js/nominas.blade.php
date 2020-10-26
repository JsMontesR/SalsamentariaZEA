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
            document.getElementById('empleado_id').value = "";
            document.getElementById('nombre').value = "";
            document.getElementById('di').value = "";
            document.getElementById('salario').value = "";
            document.getElementById('valor').value = "";
            document.getElementById('parteEfectiva').value = "";
            document.getElementById('parteCrediticia').value = "";
            document.getElementById('pagar').disabled = false;
            $('#recurso tr').removeClass("selected");
            document.getElementById('verpagos').disabled = true;
            document.getElementById('registrar').disabled = false;
            document.getElementById('eliminar').disabled = true;
            document.getElementById('modificar').disabled = true;
            // table.ajax.reload();
        }

        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            ajax: 'api/nominas/listar',
            columns: [
                {data: 'id', name: 'nominas.id', title: 'Id', className: "text-center"},
                {data: 'empleado.id', name: 'empleado.id', title: 'Id del empleado', visible: false, searchable: false},
                {data: 'empleado.name', name: 'empleado.name', title: 'Nombre del empleado', className: "text-center"},
                {
                    data: 'empleado.di',
                    name: 'empleado.di',
                    title: 'Documento de identidad',
                    render: $.fn.dataTable.render.number('.', '.', 0),
                    className: "text-center"
                },
                {
                    data: 'abonado',
                    title: 'Valor pagado',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'saldo',
                    title: 'Saldo por pagar',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'valor',
                    name: 'nominas.valor',
                    title: 'Valor de la nómina',
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ '),
                    className: "text-center"
                },
                {
                    data: 'fechapagado',
                    name: 'nominas.fechapagado',
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
                    name: 'nominas.fechapago',
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
                {data: 'created_at', name: 'nominas.created_at', title: 'Fecha de creación', className: "text-center"},
                {
                    data: 'updated_at',
                    name: 'nominas.updated_at',
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

        $('#recurso tbody').on('click', 'tr', function () {
            cargarNomina(this);
        });

        let empleadoId;

        function cargarNomina(row, data = null) {
            limpiarFormulario();
            if (data == null) {
                data = table.row(row).data();
            }
            $(row).addClass('selected');
            document.getElementById('id').value = data['id'];
            document.getElementById('empleado_id').value = data['empleado']['id'];
            document.getElementById('nombre').value = data['empleado']['name'];
            document.getElementById('fechapago').value = data['fechapago'];
            $('#di').val(data['empleado']['di']).trigger('input');
            $('#salario').val(data['empleado']['salario']).trigger('input');
            $('[name="valor"]').val(data['valor']).trigger('input');
            $('[name="saldo"]').val(data['saldo']).trigger('input');
            $('[name="valorpagado"]').val(data['valor'] - data['saldo']).trigger('input');
            $('#parteEfectiva').val(data['saldo']).trigger('input');
            empleadoId = data['empleado']['id'];
            empleados_table.columns(0).search(empleadoId).draw();
            document.getElementById('registrar').disabled = true;
            document.getElementById('verpagos').disabled = false;
            document.getElementById('eliminar').disabled = false;
            document.getElementById('modificar').disabled = false;
        }

        let empleadoSpecific;
        let empleados_table = $('#empleados').DataTable($.extend({
            serverSide: true,
            ajax: 'api/empleados/listar',
            columns: [
                {data: 'id', title: 'Id', className: "text-center"},
                {data: 'name', title: 'Nombre', className: "text-center"},
                {
                    data: 'di',
                    title: 'Documento de identidad',
                    render: $.fn.dataTable.render.number('.', '.', 0,),
                    className: "text-center"
                },
                {data: 'celular', title: 'Teléfono celular', className: "text-center"},
                {data: 'fijo', title: 'Teléfono fijo', className: "text-center"},
                {data: 'email', title: 'Correo electrónico', className: "text-center"},
                {data: 'direccion', title: 'Dirección', className: "text-center"},
                {
                    data: 'salario',
                    title: 'Salario',
                    render: $.fn.dataTable.render.number(',', '.', 0, " $"),
                    className: "text-center"
                },
                {data: 'rol.id', title: 'Id de rol', className: "text-center", visible: false, searchable: false,},
                {data: 'rol.nombre', title: 'Rol', className: "text-center"},
                {data: 'created_at', title: 'Fecha de creación', className: "text-center"},
                {data: 'updated_at', title: 'Fecha de actualización', className: "text-center"},
            ],
            drawCallback: function () {
                if (empleadoId) {
                    empleadoSpecific = empleados_table.row({search: 'applied'});
                    let foundId = empleadoSpecific.data().id;
                    if (foundId == empleadoId) {
                        cargarEmpleado(empleadoSpecific.node());
                    }
                    empleadoId = null;
                }
            }
        }, options));

        function cargarEmpleado(row) {
            let data = empleados_table.row(row).data();
            document.getElementById('empleado_id').value = data['id'];
            $(row).addClass('selected');
            document.getElementById('nombre').value = data['name'];
            $('#di').val(data['di']).trigger('input');
            $('#salario').val(data['salario']).trigger('input');
            $('#valor').val(data['salario']).trigger('input');
        }

        $('#empleados tbody').on('click', 'tr', function () {
            cargarEmpleado(this);
        });

        $("#registrar").click(function () {
            $.post('/api/nominas/crear',
                {
                    empleado_id: $("#empleado_id").val(),
                    valor: $("#valor").cleanVal(),
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
            $.post('/api/nominas/crear',
                {
                    empleado_id: $("#empleado_id").val(),
                    valor: $("#valor").cleanVal(),
                    fechapago: $("#fechapago").val(),
                }, function (data) {
                    toastr.success(data.msg);
                    $("#modalMovimientos").modal('show');
                    limpiarFormulario();
                    cargarNomina(null, data.data);
                    cargarModalPagos(data.data.id);
                    table.ajax.reload();
                }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    swal("Ha ocurrido un error", error[0], "error");
                });
                console.error(err);
            })
        });

        $("#modificar").click(function () {
            $.post('/api/nominas/modificar',
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

        $("#limpiar").click(function () {
            location.href = "{{route('nominas')}}";
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
                        $.post('api/nominas/anular', {
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

        function cargarModalPagos(isDeNomina = null) {
            darFormatoNumerico();
            let id = isDeNomina == null ? $("#id").val() : isDeNomina;
            if ($.fn.DataTable.isDataTable('#pagos_table')) {
                pagos_table.destroy();
            }
            pagos_table = $('#pagos_table').DataTable($.extend({
                serverSide: true,
                ajax: '/api/nominas/' + id + '/pagos',
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
                    movimientoable: "nomina"
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
