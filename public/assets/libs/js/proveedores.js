function registrarCliente() {

    document.form1.action = '{{ route('
    clientes.store
    ') }}';
    document.form1.submit();
}

function modificarCliente() {

    document.form1.action = '{{ route('
    clientes.update
    ') }}';
    document.form1.submit();
}

function eliminarCliente() {
    var opcion = confirm("¿Está seguro que desea eliminar el cliente seleccionado?");
    if (opcion) {
        var valor = document.getElementById('id').value;
        document.form1.action = '{{ route('
        clientes.delete
        ') }}';
        document.form1.submit();
    }

}

function limpiarCampos() {
    document.getElementById('id').value = "";
    document.getElementById('name').value = "";
    document.getElementById('email').value = "";
    document.getElementById('cedula').value = "";
    document.getElementById('telefonocelular').value = "";
    document.getElementById('telefonofijo').value = "";
    document.getElementById('direccion').value = "";
    document.getElementById('registrar').disabled = false;
}

var cambiar = function () {
    document.getElementById('registrar').disabled = true;
    document.getElementById('id').value = {!!json_encode($registro->Id)!!};
    document.getElementById('name').value = {!!json_encode($registro->Nombre)!!};
    document.getElementById('cedula').value = {!!json_encode($registro->Cedula)!!};
    document.getElementById('email').value = {!!json_encode($registro->Email)!!};
    document.getElementById('telefonofijo').value = {!!json_encode($registro->{'Telefono Fijo'})!!};
    document.getElementById('telefonocelular').value = {!!json_encode($registro->{'Telefono Celular'})!!};
    document.getElementById('direccion').value = {!!json_encode($registro->Direccion)!!};

};
var input = document.getElementById({!!json_encode($registro->Id)!!});
input.addEventListener('click', cambiar);
