$(document).ready(function () {

    let table = $('#recurso').DataTable($.extend({
        serverSide: true,
        ajax: 'api/listarproveedores',
        columns: [
            {data: 'id', title: 'Id'},
            {data: 'nombre', title: 'Nombre del proveedor'},
            {data: 'telefono', title: 'Teléfono del proveedor'},
            {data: 'direccion', title: 'Dirección del empleado'},
            {data: 'created_at', title: 'Fecha de creación'},
            {data: 'updated_at', title: 'Fecha de actualización'},
        ]
    }, options));

    $('#recurso tbody').on('click', 'tr', function () {
        document.getElementById('registrar').disabled = true;
        let data = table.row($(this)).data();
        document.getElementById('id').value = data['id'];
        document.getElementById('nombre').value = data['nombre'];
        document.getElementById('telefono').value = data['telefono'];
        document.getElementById('direccion').value = data['direccion'];
    });


    $("#registrar").click(function () {
        document.form.action = "crearproveedor";
        document.form.submit();
        table.ajax.reload();
    });

    $("#limpiar").click(function () {
        document.getElementById('id').value = "";
        document.getElementById('nombre').value = "";
        document.getElementById('telefono').value = "";
        document.getElementById('direccion').value = "";
        document.getElementById('registrar').disabled = false;
    });

    $("#modificar").click(function () {
        document.form.action = "modificarproveedor";
        document.form.submit();
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
                    document.form.action = "borrarproveedor";
                    document.form.submit();
                }
            });
    });


});
