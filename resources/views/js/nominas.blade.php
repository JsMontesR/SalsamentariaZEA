<script>
    $(document).ready(function () {
        $('.number').mask('000.000.000.000.000', {reverse: true});

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
        }

        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            ajax: 'api/nominas/listar',
            columns: [
                {data: 'id', title: 'Id', className: "text-center"},
                {data: 'empleado.id', title: 'Id del empleado', visible: false, searchable: false},
                {data: 'empleado.name', title: 'Nombre del empleado', className: "text-center"},
                {
                    data: 'empleado.di',
                    title: 'Documento de identidad',
                    render: $.fn.dataTable.render.number('.', '.', 0),
                    className: "text-center"
                },
                {
                    data: 'empleado.salario',
                    title: 'Salario',
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ '),
                    className: "text-center"
                },
                {
                    data: 'valor',
                    title: 'Valor pagado',
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ '),
                    className: "text-center"
                },
                {data: 'created_at', title: 'Fecha de creación', className: "text-center"},
                {data: 'updated_at', title: 'Fecha de actualización', className: "text-center"},
            ]
        }, options));

        $('#recurso tbody').on('click', 'tr', function () {
            limpiarFormulario();
            $(this).addClass('selected');
            document.getElementById('pagar').disabled = true;
            let data = table.row(this).data();
            document.getElementById('id').value = data['id'];
            document.getElementById('empleado_id').value = data['empleado']['id'];
            document.getElementById('nombre').value = data['empleado']['name'];
            $('#di').val(data['empleado']['di']).trigger('input');
            $('#salario').val(data['empleado']['salario']).trigger('input');
            $('#valor').val(data['valor']).trigger('input');
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
            ]
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

        $("#pagar").click(function () {
            $.post('api/nominas/pagar', {
                empleado_id: $("#empleado_id").val(),
                parteEfectiva: $("#parteEfectiva").cleanVal(),
                parteCrediticia: $("#parteCrediticia").cleanVal(),
            }, function (data) {
                swal("¡Operación exitosa!", data.msg, "success");
                limpiarFormulario()
                table.ajax.reload();
            }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    toastr.error(error[0]);
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
                                toastr.error(error[0]);
                            });
                            console.error(err);
                        })
                    }
                });
        });
    });
</script>
