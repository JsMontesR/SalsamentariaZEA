<script>
    $(document).ready(function () {

        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            ajax: '/api/reportes/listarFacturasPorCobrar',
            columns: [
                {data: 'id', name: 'ventas.id', title: 'Id de la venta', className: "text-center font-weight-bold"},
                {
                    data: 'cliente.name',
                    name: 'cliente.name',
                    title: 'Nombre del cliente',
                    className: "text-center",
                },
                {
                    data: 'empleado.name',
                    name: 'empleado.name',
                    title: 'Nombre del vendedor',
                    className: "text-center",
                },
                {
                    data: 'fechapago',
                    name: 'ventas.fechapago',
                    title: 'Fecha límite de pago',
                    className: "text-center",
                    render: function (data) {
                        if (data) {
                            return '<a>' + data + '</a>';
                        } else {
                            return '<a class="text-warning">Sin fecha límite de pago</a>';
                        }
                    }
                },
                {
                    data: 'abonado',
                    title: 'Valor cobrado',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'saldo',
                    title: 'Saldo por cobrar',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'valor',
                    title: 'Valor de la venta',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'costo',
                    title: 'Costo de la venta',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'utilidad',
                    title: 'Utilidad de la venta',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'created_at',
                    title: 'Fecha de creación',
                    className: "text-center"
                },
                {
                    data: 'updated_at',
                    title: 'Fecha de actualización',
                    className: "text-center"
                },
            ]
        }, options));

    });
</script>

