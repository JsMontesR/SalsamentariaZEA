<script>
    $(document).ready(function () {

        function borrarTabla() {
            if ($.fn.DataTable.isDataTable('#recurso')) {
                $('#recurso').DataTable().destroy();
            }
        }

        llenarTabla();

        function llenarTabla(fechaInicio = '', fechaFin = '', cierre_id = '') {

            borrarTabla();

            let table = $('#recurso').DataTable($.extend({
                serverSide: true,
                processing: true,
                ajax: {
                    data: {
                        fechaInicio: fechaInicio,
                        fechaFin: fechaFin,
                        cierre_id: cierre_id,
                    },
                    url: "/api/reportes/listarPartesBalance",
                },
                columns: [
                    {data: 'id', name: 'id', title: 'Id', className: "text-center font-weight-bold"},
                    {data: 'concepto', name: 'concepto', title: 'Concepto', className: "text-center font-weight-bold"},
                    {
                        data: 'naturaleza',
                        name: 'naturaleza',
                        title: 'Naturaleza',
                        className: "text-center",
                        render: function (data) {
                            if (data == "Ingreso") {
                                return '<span class="text-success"><i class="fas fa-dollar-sign"></i><br>' + data + '</span>';
                            } else if (data == "Egreso") {
                                return '<span class="text-danger"><i class="fas fa-dollar-sign"></i><br>' + data + '</span>';
                            }
                        }
                    },
                    {
                        data: 'total',
                        title: 'Total aporte',
                        className: "text-center",
                        render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                    },
                    {
                        data: 'created_at',
                        title: 'Fecha de creación',
                        className: "text-center"
                    }
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
        });

        $('#fechaFin,#fechaInicio').change(function () {
            limpiarFiltroCierres();
        });

        $('#limpiar').click(function () {
            limpiarFiltroCierres();
            limpiarFiltroFechas();
        });

        $('#aplicarFiltros').click(function () {
            aplicarFiltros();
        });

        $('#verimpresion').click(function () {
            $("#form").attr('action', "{{ route('listarpartesbalance') }}");
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

