<script>
    $(document).ready(function () {

        function limpiarFormulario() {
            document.getElementById('id').value = "";
            document.getElementById('nombre').value = "";
            document.getElementById('telefono').value = "";
            document.getElementById('direccion').value = "";
            document.getElementById('registrar').disabled = false;
            $('#recurso tr').removeClass("selected");
        }

        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            ajax: 'api/proveedores/listar',
            columns: [
                {data: 'id', title: 'Id', className: "text-center"},
                {data: 'nombre', title: 'Nombre del proveedor', className: "text-center"},
                {data: 'telefono', title: 'Teléfono del proveedor', className: "text-center"},
                {data: 'direccion', title: 'Dirección del empleado', className: "text-center"},
                {data: 'created_at', title: 'Fecha de creación', className: "text-center"},
                {data: 'updated_at', title: 'Fecha de actualización', className: "text-center"},
            ]
        }, options));

        $('#recurso tbody').on('click', 'tr', function () {
            limpiarFormulario();
            $(this).addClass('selected');
            document.getElementById('registrar').disabled = true;
            let data = table.row($(this)).data();
            document.getElementById('id').value = data['id'];
            document.getElementById('nombre').value = data['nombre'];
            document.getElementById('telefono').value = data['telefono'];
            document.getElementById('direccion').value = data['direccion'];
        });


        $("#registrar").click(function () {
            $.post('api/proveedores/crear', $('#form').serialize(), function (data) {
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

        $("#modificar").click(function () {
            $.post('api/proveedores/modificar', $('#form').serialize(), function (data) {
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
                        $.post('api/proveedores/borrar', $('#form').serialize(), function (data) {
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
