$(document).ready(function () {


    $("#verpagos").click(function () {
        $.ajax({
            url: "api/entradas/" + $("#id").val() + "/pagos",
            type: "get",
            success: function (data) {
                console.log(data);
            },
            error: function (err) {
                console.warn(err);
            }
        })
        if (!$.fn.DataTable.isDataTable('#pagos_table')) {
            console.log("hola");
            let pagos_table = $('#pagos_table').DataTable($.extend({
                serverSide: true,
                ajax: 'api/entradas/' + $("#id").val() + '/pagos',
                columns: [
                    {data: 'id', title: 'Id', className: "text-center"},
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
        }
    })

    $("#pagar").click(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post('api/entradas/pagar',
            {
                id: $("#id").val(),
                parteCrediticia: $("#parteCrediticia").val(),
                parteEfectiva: $("#parteEfectiva").val()
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
    })

});
