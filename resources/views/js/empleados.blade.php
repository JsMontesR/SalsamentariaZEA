<script>
    $(document).ready(function () {

        function limpiarFormulario() {
            document.getElementById('id').value = "";
            document.getElementById('name').value = "";
            document.getElementById('di').value = "";
            document.getElementById('celular').value = "";
            document.getElementById('fijo').value = "";
            document.getElementById('email').value = "";
            document.getElementById('password').value = "";
            document.getElementById('direccion').value = "";
            document.getElementById('salario').value = "";
            document.getElementById('rol_id').value = "";
            document.getElementById('registrar').disabled = false;
            $('#recurso tr').removeClass("selected");
        }

        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            ajax: 'api/empleados/listar',
            columns: [
                {data: 'id', name: 'users.id', title: 'Id', className: "text-center"},
                {data: 'name', name: 'users.name', title: 'Nombre', className: "text-center"},
                {
                    data: 'di',
                    name: 'users.di',
                    title: 'Documento de identidad',
                    render: $.fn.dataTable.render.number('.', '.', 0,),
                    className: "text-center"
                },
                {data: 'celular', name: 'users.celular', title: 'Teléfono celular', className: "text-center"},
                {data: 'fijo', name: 'users.fijo', title: 'Teléfono fijo', className: "text-center"},
                {data: 'email', name: 'users.email', title: 'Correo electrónico', className: "text-center"},
                {data: 'direccion', name: 'users.direccion', title: 'Dirección', className: "text-center"},
                {
                    data: 'salario',
                    name: 'users.salario',
                    title: 'Salario',
                    render: $.fn.dataTable.render.number(',', '.', 0, " $"),
                    className: "text-center"
                },
                {
                    data: 'rol.id',
                    name: 'rol.id',
                    title: 'Id de rol',
                    className: "text-center",
                    visible: false,
                    searchable: false,
                },
                {data: 'rol.nombre', name: 'rol.nombre', title: 'Rol', className: "text-center"},
                {data: 'created_at', name: 'users.created_at', title: 'Fecha de creación', className: "text-center"},
                {
                    data: 'updated_at',
                    name: 'users.updated_at',
                    title: 'Fecha de actualización',
                    className: "text-center"
                },
            ]
        }, options));

        $('#recurso tbody').on('click', 'tr', function () {
            limpiarFormulario();
            $(this).addClass('selected');
            document.getElementById('registrar').disabled = true;
            let data = table.row(this).data();
            document.getElementById('id').value = data['id'];
            document.getElementById('name').value = data['name'];
            document.getElementById('di').value = data['di'];
            document.getElementById('celular').value = data['celular'];
            document.getElementById('fijo').value = data['fijo'];
            document.getElementById('email').value = data['email'];
            document.getElementById('direccion').value = data['direccion'];
            document.getElementById('salario').value = data['salario'];
            document.getElementById('rol_id').value = data['rol']['id'];
        });

        $("#registrar").click(function () {
            $.post('api/empleados/crear', $('#form').serialize(), function (data) {
                swal("¡Operación exitosa!", data.msg, "success");
                limpiarFormulario()
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

        $("#modificar").click(function () {
            $.post('api/empleados/modificar', $('#form').serialize(), function (data) {
                swal("¡Operación exitosa!", data.msg, "success");
                limpiarFormulario()
                table.ajax.reload();
            }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    swal("Ha ocurrido un error", error[0], "error");
                });
                console.error(err);
            })
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
                        $.post('api/empleados/borrar', $('#form').serialize(), function (data) {
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
    });
</script>
