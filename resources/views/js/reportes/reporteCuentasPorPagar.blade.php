<script>
    $(document).ready(function () {

        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            ajax: '/api/reportes/listarCuentasPorPagar',
            columns: [
                {data: 'id', name: 'entradas.id', title: 'Id', className: "text-center font-weight-bold"},
                {
                    data: 'proveedor.nombre',
                    name: 'proveedor.nombre',
                    title: 'Nombre del proveedor',
                    className: "text-center",
                },
                {
                    data: 'empleado.name',
                    name: 'empleado.name',
                    title: 'Nombre del empleado',
                    className: "text-center",
                },
                {
                    data: 'fechapago',
                    name: 'entradas.fechapago',
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
                    title: 'Valor pagado',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'saldo',
                    title: 'Saldo por pagar',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {
                    data: 'valor',
                    title: 'Valor de la entrada',
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

