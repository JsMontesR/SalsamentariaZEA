<script>
    $(document).ready(function () {
        $.ajax({
            url: "api/movimientos/listar",
            type: "get",
            success: function (data) {
                console.log(data);
            },
            error: function (err) {
                console.warn(err);
            }
        })

        $('#movimiento').DataTable($.extend({
            serverSide: true,
            ajax: 'api/movimientos/listar',
            columns: [
                {data: 'id', title: 'Id', className: "text-center"},
                {
                    data: 'tipo', title: 'Tipo de movimiento', className: "text-center", render: function (data) {
                        if (data == "Ingreso") {
                            return '<span class="text-success"><i class="fas fa-dollar-sign"></i><br>' + data + '</span>';
                        } else if (data == "Egreso") {
                            return '<span class="text-danger"><i class="fas fa-dollar-sign"></i><br>' + data + '</span>';
                        }
                    }
                },
                {data: 'movimientoable_id', title: 'No. de movimiento', className: "text-center"},
                {
                    data: 'movimientoable_type',
                    title: 'Operación asociada',
                    className: "text-center",
                    render: function (data, type, row) {
                        let msg = "";
                        switch (data) {
                            case "App\\Entrada":
                                if (row["tipo"] == "Ingreso") {
                                    msg = "Devolución de entrada";
                                } else if (row["tipo"] == "Egreso") {
                                    msg = "Entrada a inventario";
                                }
                                return '<i class="fas fa-dolly"></i><br>' + msg;
                            case "App\\Retiro":
                                if (row["tipo"] == "Ingreso") {
                                    msg = "Devolución de retiro";
                                } else if (row["tipo"] == "Egreso") {
                                    msg = "Retiro de inventario";
                                }
                                return '<i class="fas fa-sign-out-alt"></i><br>' + msg;
                            case "App\\Venta":
                                if (row["tipo"] == "Ingreso") {
                                    msg = "Venta";
                                } else if (row["tipo"] == "Egreso") {
                                    msg = "Devolución de venta";
                                }
                                return '<i class="fas fa-shopping-cart"></i><br>' + msg;
                            case "App\\Nomina":
                                if (row["tipo"] == "Ingreso") {
                                    msg = "Devolución de nómina";
                                } else if (row["tipo"] == "Egreso") {
                                    msg = "Pago de nómina";
                                }
                                return '<i class="fas fa-hand-holding-usd"></i><br>' + msg;
                        }
                    }
                },
                {data: 'empleado.name', title: 'Empleado que realizó el movimiento', className: "text-center"},
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
                {
                    data: 'valor',
                    title: 'Total de dinero',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ ')
                },
                {data: 'created_at', title: 'Fecha de creación', className: "text-center"}
            ],
            order: [[0, "desc"]]
        }, options));

    });
</script>
