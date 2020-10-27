<script>
    $(document).ready(function () {

        llenarTabla();

        function llenarTabla(fechaInicio = '', fechaFin = '', cierre_id = '') {

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
                        cierre_id: cierre_id,
                    },
                    url: "/api/reportes/listarventas",
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

        let tablaCierres = $('#cierres').DataTable($.extend({
            serverSide: true,
            ajax: '/api/cierres/listar',
            columns: [
                {data: 'id', title: 'Número de cierre', className: "text-center"},
                {data: 'created_at', title: 'Fecha del cierre', className: "text-center"},
            ],
            responsive: true,
        }, options));

        $('#cierres tbody').on('click', 'tr', function () {
            $('#cierres tr').removeClass("selected");
            $(this).addClass("selected");
            let data = tablaCierres.row(this).data();
            console.log(data.id);
            $('#cierre_id').val(data.id);
            limpiarFiltroFechas();
            aplicarFiltros();
        });

        $('#fechaFin,#fechaInicio').change(function () {
            limpiarFiltroCierres();
            aplicarFiltros();
        });

        $('#limpiar').click(function () {
            limpiarFiltroCierres();
            limpiarFiltroFechas();
            llenarTabla();
        });

        $('#verimpresion').click(function () {
            $("#form").attr('action', "{{ route('listarventas') }}");
            $("#form").submit();
        });

        function limpiarFiltroCierres() {
            $('#cierre_id').val("");
            $('#cierres tr').removeClass("selected");
        }

        function limpiarFiltroFechas() {
            $('#fechaInicio').val("");
            $('#fechaFin').val("");
        }

        function aplicarFiltros() {
            llenarTabla($("#fechaInicio").val(), $("#fechaFin").val(), $("#cierre_id").val());
        }

    });
</script>

