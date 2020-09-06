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
    }

    let table = $('#recurso').DataTable($.extend({
        serverSide: true,
        ajax: 'api/listarempleados',
        columns: [
            {data: 'id', title: 'Id', className: "text-center"},
            {data: 'name', title: 'Nombre'},
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

    $('#recurso tbody').on('click', 'tr', function () {
        limpiarFormulario();
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
        $.post('api/crearempleado', $('#form').serialize(), function (data) {
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
        $.post('api/modificarempleado', $('#form').serialize(), function (data) {
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
                    $.post('api/borrarempleado', $('#form').serialize(), function (data) {
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
