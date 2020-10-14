<script>
    $(document).ready(function () {

        llenarTabla();

        function llenarTabla(fechaInicio = '', fechaFin = '') {

            if ($.fn.DataTable.isDataTable('#recurso')) {
                $('#recurso').DataTable().destroy();
            }

            let table = $('#recurso').DataTable($.extend({
                serverSide: true,
                processing: true,
                ajax: {
                    data: {
                        fechaInicio: fechaInicio,
                        fechaFin: fechaFin,
                    },
                    url: "/api/reportes/listarVentas",
                },
                columns: [
                    {data: 'id', name: 'ventas.id', title: 'Id', className: "text-center font-weight-bold"},
                    {
                        data: 'cliente.id',
                        name: 'cliente.id',
                        title: 'Id del cliente',
                        visible: false,
                        searchable: false,
                        orderable: false
                    },
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
                        data: 'fechapagado',
                        name: 'ventas.fechapagado',
                        title: 'Fecha de pago',
                        className: "text-center",
                        render: function (data) {
                            if (data) {
                                return '<a class="text-success">' + data + '</a>';
                            } else {
                                return '<a class="text-danger">Sin pagar</a>';
                            }
                        }
                    },
                    {
                        data: 'fechapago',
                        name: 'ventas.fechapago',
                        title: 'Fecha límite de pago',
                        className: "text-center"
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
        }

        $('[name="filtro"]').change(function () {
            llenarTabla($("#fechaInicio").val(), $("#fechaFin").val());
        });

        $('#limpiar').click(function () {

        });

    });
</script>

