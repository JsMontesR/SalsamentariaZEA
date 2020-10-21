<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function darFormatoNumerico() {
            $('.money').mask('#.##0', {reverse: true});
        }

        darFormatoNumerico();

        function limpiarFormulario() {
            document.getElementById('id').value = "";
            document.getElementById('nombre').value = "";
            document.getElementById('costo').value = "";
            document.getElementById('registrar').disabled = false;
            $('#recurso tr').removeClass("selected");
        }

        let table = $('#recurso').DataTable($.extend({
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
            ]
        }, options));

        $('#recurso tbody').on('click', 'tr', function () {
            limpiarFormulario();
            $(this).addClass('selected');
            document.getElementById('registrar').disabled = true;
            let data = table.row($(this)).data();
            document.getElementById('id').value = data['id'];
            document.getElementById('nombre').value = data['nombre'];
            document.getElementById('costo').value = data['costo'];
        });


        $("#registrar").click(function () {
            $.post('api/tiposervicios/crear', {
                nombre: $("#nombre").val(),
                costo: $("#costo").cleanVal(),
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
        });


        $("#limpiar").click(function () {
            limpiarFormulario();
        });

        $("#modificar").click(function () {
            $.post('api/tiposervicios/modificar', {
                id: $("#id").val(),
                nombre: $("#nombre").val(),
                costo: $("#costo").cleanVal(),
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
                        $.post('api/tiposervicios/borrar', {
                            id: $("#id").val()
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
    });
</script>
