<script>
    $(document).ready(function () {

        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            ajax: '/api/reportes/listarProductos',
            columns: [
                {data: 'id', name: 'productos.id', title: 'Id', className: "text-center"},
                {data: 'nombre', name: 'productos.nombre', title: 'Nombre', className: "text-center"},
                {data: 'categoria', name: 'productos.categoria', title: 'Categoría', className: "text-center"},
                {
                    data: 'costo',
                    name: 'productos.costo',
                    title: 'Costo Unitario/Kg',
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ '),
                    className: "text-center"
                },
                {
                    data: 'utilidad',
                    name: 'productos.utilidad',
                    title: 'Utilidad Unitaria/Kg',
                    className: "text-center",
                    render: function (data, type, full, meta) {
                        return +data + '%';
                    }
                },
                {
                    data: 'precio',
                    name: 'productos.precio',
                    title: 'Precio Unitario/Kg',
                    render: $.fn.dataTable.render.number(',', '.', 0, '$ '),
                    className: "text-center"
                },
                {
                    data: 'stock',
                    name: 'productos.stock',
                    title: 'Unidades/g en stock',
                    render: function (data, type, row) {
                        if (row["categoria"] == "Granel") {
                            return +data + ' gramos';
                        } else if (row["categoria"] == "Unitario") {
                            return +data + ' unidades';
                        }

                    },
                    className: "text-center"
                },
                {
                    data: 'tipo.id',
                    name: 'tipo.id',
                    title: 'Id de tipo',
                    visible: false,
                    searchable: false,
                    className: "text-center"
                },
                {data: 'tipo.nombre', name: 'tipo.nombre', title: 'Tipo de producto', className: "text-center"},
                {data: 'created_at', name: 'created_at', title: 'Fecha de creación', className: "text-center"},
                {data: 'updated_at', name: 'updated_at', title: 'Fecha de actualización', className: "text-center"},
            ]
        }, options));

        $('#verimpresion').click(function () {
            $("#form").attr('action', "{{ route('listarproductos') }}");
            $("#form").submit();
        });


    });
</script>

