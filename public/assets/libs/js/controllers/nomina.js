$(document).ready(function () {

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
        document.getElementById('di').value = data['empleado']['di'];
        document.getElementById('salario').value = data['empleado']['salario'];
        document.getElementById('valor').value = data['valor'];
    });

    let empleados_table = $('#empleados').DataTable($.extend({
        serverSide: true,
        ajax: 'api/empleados/listar',
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

    $('#empleados tbody').on('click', 'tr', function () {
        let data = empleados_table.row(this).data();
        document.getElementById('empleado_id').value = data['id'];
        document.getElementById('nombre').value = data['name'];
        document.getElementById('di').value = data['di'];
        document.getElementById('salario').value = data['salario'];
        document.getElementById('parteEfectiva').value = data['salario'];
    });

    $("#pagar").click(function () {
        $.post('api/nominas/pagar', $('#form').serialize(), function (data) {
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
                    $.post('api/nominas/anular', $('#form').serialize(), function (data) {
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
