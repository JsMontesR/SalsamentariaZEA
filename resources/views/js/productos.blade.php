<script>
    $(document).ready(function () {

        function caracterizarFormulario(tipo) {
            if (tipo == undefined) {
                document.getElementById('label_costo').innerText = "Costo por unidad/kg:";
                document.getElementById('label_utilidad').innerText = "Utilidad (%) por unidad/kg:";
                document.getElementById('label_precio').innerText = "Precio por unidad/kg:";
                document.getElementById('label_stock').innerText = "Unidades/g en stock:";
            } else if (tipo == "Granel") {
                document.getElementById('label_costo').innerText = "Costo por kilo (kg):";
                document.getElementById('label_utilidad').innerText = "Utilidad (%) por kilo (kg):";
                document.getElementById('label_precio').innerText = "Precio por kilo (kg):";
                document.getElementById('label_stock').innerText = "Gramos en stock (g):"
            } else if (tipo == "Unitario") {
                document.getElementById('label_costo').innerText = "Costo por unidad:";
                document.getElementById('label_utilidad').innerText = "Utilidad (%) por unidad:";
                document.getElementById('label_precio').innerText = "Precio por unidad:";
                document.getElementById('label_stock').innerText = "Unidades en stock:"
            }
        }

        function limpiarFormulario() {
            document.getElementById('id').value = "";
            document.getElementById('nombre').value = "";
            document.getElementById('categoria').value = "";
            document.getElementById('costo').value = "";
            document.getElementById('utilidad').value = "";
            document.getElementById('precio').value = "";
            document.getElementById('stock').value = "";
            document.getElementById('tipo_id').value = "";
            document.getElementById('registrar').disabled = false;
            $('#recurso tr').removeClass("selected");
            caracterizarFormulario(undefined);
        }

        $("#categoria").change(function () {
            caracterizarFormulario($(this).val());
        })

        let table = $('#recurso').DataTable($.extend({
            serverSide: true,
            ajax: 'api/productos/listar',
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
                    data: 'stock', name: 'productos.stock', title: 'Unidades/g en stock', render: function (data, type, row) {
                        if (row["categoria"] == "Granel") {
                            return +data + ' gramos';
                        } else if (row["categoria"] == "Unitario") {
                            return +data + ' unidades';
                        }

                    }, className: "text-center"
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
                {data: 'barcode', name: 'productos.barcode', title: 'Código de barras', className: "text-center"},
                {data: 'created_at', name: 'created_at', title: 'Fecha de creación', className: "text-center"},
                {data: 'updated_at', name: 'updated_at', title: 'Fecha de actualización', className: "text-center"},
            ]
        }, options));

        $('#recurso tbody').on('click', 'tr', function () {
            limpiarFormulario();
            $(this).addClass('selected');
            document.getElementById('registrar').disabled = true;
            let data = table.row(this).data();
            document.getElementById('id').value = data['id'];
            document.getElementById('nombre').value = data['nombre'];
            document.getElementById('categoria').value = data['categoria'];
            document.getElementById('costo').value = data['costo'];
            document.getElementById('barcode').value = data['barcode'];
            document.getElementById('utilidad').value = data['utilidad'];
            document.getElementById('precio').value = data['precio'];
            document.getElementById('stock').value = data['stock'];
            document.getElementById('tipo_id').value = data['tipo']['id'];
            caracterizarFormulario(data['categoria']);
        });

        $("#registrar").click(function () {
            $.post('api/productos/crear', $('#form').serialize(), function (data) {
                swal("¡Operación exitosa!", data.msg, "success");
                limpiarFormulario()
                table.ajax.reload();
            }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    toastr.error(error[0]);
                });
                console.error(err);
            })
        });

        $("#limpiar").click(function () {
            limpiarFormulario();
        });

        $("#modificar").click(function () {
            $.post('api/productos/modificar', $('#form').serialize(), function (data) {
                swal("¡Operación exitosa!", data.msg, "success");
                limpiarFormulario()
                table.ajax.reload();
            }).fail(function (err) {
                $.each(err.responseJSON.errors, function (i, error) {
                    toastr.error(error[0]);
                });
                console.error(err);
            })
        });

        $("#eliminar").click(function () {
            swal({
                title: "¿Estas seguro?",
                text: "¡Una vez borrado no será posible recuperarlo!",
                icon: "warning",
                dangerMode: true,
                buttons: ["Cancelar", "Borrar"]
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.post('api/productos/borrar', $('#form').serialize(), function (data) {
                            swal("¡Operación exitosa!", data.msg, "success");
                            limpiarFormulario()
                            table.ajax.reload();
                        }).fail(function (err) {
                            swal("Ha ocurrido un error", "No se puede borrar el recurso, es posible que otra entidad del negocio esté haciendo referencia a este.", "error");
                            console.error(err);
                        })
                    }
                });
        });

        $("#costo,#utilidad").keyup(function () {
            var costo = parseInt(document.getElementById('costo').value, 10);
            var utilidad = parseInt(document.getElementById('utilidad').value, 10);
            if (!isNaN(costo) && !isNaN(utilidad)) {
                document.getElementById('precio').value = Math.round(costo * (1 + utilidad / 100));
            }
        });

        $("#precio").keyup(function () {
            var precio = parseInt(document.getElementById('precio').value, 10);
            var costo = parseInt(document.getElementById('costo').value, 10);
            var utilidad = parseInt(document.getElementById('utilidad').value, 10);
            if (!isNaN(costo) && !isNaN(utilidad) && !isNaN(precio)) {
                document.getElementById('utilidad').value = Math.round(((precio / costo) - 1) * 100);
            }
        });
    })
    ;
</script>
