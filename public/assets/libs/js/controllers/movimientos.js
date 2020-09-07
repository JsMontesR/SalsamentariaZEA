$(document).ready(function () {
    $.ajax({
        url: "api/listarmovimientos",
        type: "get",
        success: function (data) {
            console.log(data);
        },
        error: function (err) {
            console.warn(err);
        }
    })

    let table = $('#movimiento').DataTable($.extend({
        serverSide: true,
        ajax: 'api/listarmovimientos',
        columns: [
            {data: 'id', title: 'Id', className: "text-center"},
            {
                data: 'ingreso', title: 'Tipo de movimiento', className: "text-center", render: function (data) {
                    if(data){
                        return '<span class="text-success"><i class="fas fa-dollar-sign"></i><br>Ingreso</span>';
                    }else{
                        return '<span class="text-danger"><i class="fas fa-dollar-sign"></i><br>Egreso</span>';
                    }
                }
            },
            {
                data: 'movimientoable_type',
                title: 'Operación realizada',
                className: "text-center",
                render: function (data, type, row) {
                    switch (data) {
                        case "App\\Entrada":
                            return '<i class="fas fa-hand-holding-usd"></i><br>Entrada a inventario';
                        case "App\\Retiro":
                            return '<i class="fas fa-hand-holding-usd"></i><br>Retiro de inventario';
                        case "App\\Venta":
                            return '<i class="fas fa-hand-holding-usd"></i><br>Venta';
                        case "App\\Nomina":
                            return '<i class="fas fa-hand-holding-usd"></i><br>Pago de nómina';
                    }
                }
            },
            {data: 'movimientoable.empleado.name', title:'Empleado que realizó el movimiento', className: "text-center"},
            {data: 'parteEfectiva', title: 'Parte efectiva', className: "text-center", render: $.fn.dataTable.render.number(',', '.', 0, '$ ')},
            {data: 'parteCrediticia', title: 'Parte crediticia', className: "text-center", render: $.fn.dataTable.render.number(',', '.', 0, '$ ')},
            {data: 'movimientoable.valor', title:'Total de dinero', className: "text-center", render: $.fn.dataTable.render.number(',', '.', 0, '$ ')},
            {data: 'created_at', title: 'Fecha de creación', className: "text-center"}
        ]
    }, options));

});
