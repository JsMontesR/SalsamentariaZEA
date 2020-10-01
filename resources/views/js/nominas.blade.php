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
                    data: 'valor',
                    name: 'nominas.valor',
                    title: 'Valor',
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ '),
                    className: "text-center"
                },
                {
                    data: 'fechapagado',
                    name: 'fechapagado',
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
                    className: "text-center"
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

        $('#recurso tbody').on('click', 'tr', function () {
            limpiarFormulario();
            $(this).addClass('selected');
            let data = table.row(this).data();
            document.getElementById('id').value = data['id'];
            document.getElementById('empleado_id').value = data['empleado']['id'];
            document.getElementById('nombre').value = data['empleado']['name'];
            $('#di').val(data['empleado']['di']).trigger('input');
            $('#salario').val(data['empleado']['salario']).trigger('input');
            $('[name="valor"]').val(data['valor']).trigger('input');
            $('[name="saldo"]').val(data['saldo']).trigger('input');
            $('[name="valorpagado"]').val(data['valor'] - data['saldo']).trigger('input');
            document.getElementById('registrar').disabled = true;
            document.getElementById('verpagos').disabled = false;
            document.getElementById('eliminar').disabled = false;
            document.getElementById('modificar').disabled = false;
        });

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
        }, options));

        $('#empleados tbody').on('click', 'tr', function () {
            let data = empleados_table.row(this).data();
            document.getElementById('empleado_id').value = data['id'];
            document.getElementById('nombre').value = data['name'];
            $('#di').val(data['di']).trigger('input');
            $('#salario').val(data['salario']).trigger('input');
            $('#valor').val(data['salario']).trigger('input');
            $('#parteEfectiva').val(data['salario']).trigger('input');
        });

        $("#registrar").click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
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
                        $.post('api/nominas/anular', {
                            id: $('#id').val()
                        }, function (data) {
                            swal("¡Operación exitosa!", data.msg, "success");
                            limpiarFormulario()
                            table.ajax.reload();
                        }).fail(function (err) {
                            $.each(err.responseJSON.errors, function (i, error) {
                                swal("Ha ocurrido un error", error[0], "error");
                            });
                            console.error(err);
                        })
                    }
                });
        });


        /*
        SECCION MODAL
        */

        let pagos_table;
        // $("#modalMovimientos").on('shown.bs.modal', function () {
        //     $('[name="valor"]').trigger('input');
        //     $('[name="saldo"]').trigger('input');
        //     $('[name="valorpagado"]').trigger('input');
        // });
        $("#verpagos").click(function () {

            $.ajax({
                url: "/api/nominas/" + $("#id").val() + "/pagos",
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
                ajax: '/api/nominas/' + $("#id").val() + '/pagos',
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
            $.post('/api/nominas/pagar',
                {
                    id: $("#id").val(),
                    parteCrediticia: $("#parteCrediticia").cleanVal(),
                    parteEfectiva: $("#parteEfectiva").cleanVal()
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
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
