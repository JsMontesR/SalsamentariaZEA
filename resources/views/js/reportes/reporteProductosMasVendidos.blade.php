<script>
    $(document).ready(function () {

        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            ajax: '/api/reportes/listarProductosMasVendidos',
            columns: [
                {data: 'id', name: 'id', title: 'Id', className: "text-center"},
                {data: 'nombre', name: 'nombre', title: 'Nombre', className: "text-center"},
                {data: 'categoria', name: 'categoria', title: 'Categoría', className: "text-center"},
                {
                    data: 'total', name: 'total', title: 'Unidades/g en stock', render: function (data, type, row) {
                        if (row["categoria"] == "Granel") {
                            return +data + ' gramos';
                        } else if (row["categoria"] == "Unitario") {
                            return +data + ' unidades';
                        }

                    }, className: "text-center"
                },
            ]
        }, options));

        $('#verimpresion').click(function () {
            $("#form").attr('action', "{{ route('listarproductos') }}");
            $("#form").submit();
        });


    });
</script>

