<script>
    $(document).ready(function () {
        // $.ajax({
        //     url: "/api/reportes/listarClientesQueMenosCompran",
        //     type: "get",
        //     success: function (data) {
        //         console.log(data);
        //     },
        //     error: function (err) {
        //         console.warn(err);
        //     }
        // })
        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            ajax: '/api/reportes/listarClientesQueMasCompran',
            columns: [
                {data: 'id', name: 'id', title: 'Id del cliente', className: "text-center"},
                {data: 'name', name: 'name', title: 'Nombre del cliente', className: "text-center"},
                {
                    data: 'di',
                    name: 'di',
                    title: 'Documento de identidad',
                    render: $.fn.dataTable.render.number('.', '.', 0,),
                    className: "text-center"
                },
                {data: 'count', name: 'count', title: 'Total de compras', className: "text-center"},
            ]
        }, options));

    });
</script>

