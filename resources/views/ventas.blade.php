@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Ventas</h1>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Detalle de la venta</h3>
            </div>
            <div class="card-body">
                <form id="form" name="form" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Id:</label>
                        <div class="col-md-8">
                            <input readonly="readonly" id="id" class="form-control"
                                   name="id">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Cliente:</label>
                        <div class="col-md-8">
                            <input id="cliente_id" readonly
                                   class="form-control"
                                   name="cliente_id" required="required">
                        </div>
                    </div>
                    <div class="card mb-3 border border-dark">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="clientes"
                                       class="table table-bordered dt-responsive table-hover row-cursor-hand"
                                       style="width:100%">
                                    <tbody></tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Productos:</label>
                    </div>

                    <div class="row">
                        <div id="productos_container" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 p-3">
                            <div class="card shadow mb-4 form-control">
                                <div class="card-header py-3">
                                    <h3 class="m-0 font-weight-bold text-primary text-center">Productos disponibles</h3>
                                </div>
                                <div class="card-body">
                                    <table id="productos_table"
                                           class="table table-bordered dt-responsive table-hover"
                                           style="width:100%">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 p-3">
                            <div
                                class="card shadow mb-4 form-control">
                                <div class="card-header py-3">
                                    <h3 class="m-0 font-weight-bold text-primary text-center">Carrito de compras</h3>
                                </div>
                                <div id="card_productos_carrito_table"
                                     class="card-body">
                                    <table id="productos_carrito_table"
                                           class="table table-bordered dt-responsive wrap table-hover"
                                           style="width:100%">
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Valor total:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input id="valor" type="text" readonly="readonly"
                                   class="form-control money"
                                   name="valor" required autocomplete="valor">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Fecha límite de pago:</label>
                        <div class="col-md-8">
                            <input id="fechapago" type="date"
                                   class="form-control"
                                   name="fechapago" required>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Fecha de pago:</label>

                        <div class="col-md-8">
                            <input id="fechapagado" type="date" readonly="readonly"
                                   class="form-control"
                                   name="fechapagado" required>
                        </div>
                    </div>
                    <input id="tipoimpresion" name="tipoimpresion" hidden>
                </form>
                <br>
                <div class="row btn-toolbar justify-content-center">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 py-2 input-group-prepend be-addon">
                        <button id="registrar" tabindex="-1" type="button" class="btn btn-primary container-fluid">
                            Registrar
                        </button>
                        <button id="otherregister" tabindex="-1" data-toggle="dropdown" type="button"
                                class="btn btn-primary-link dropdown-toggle dropdown-toggle-split"
                                aria-expanded="false"><span
                                class="sr-only">Toggle Dropdown</span></button>
                        <div class="dropdown-menu" x-placement="top-start"
                             style="position: absolute; transform: translate3d(99px, -2px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <button id="registrarycobrar" class="dropdown-item row-cursor-hand">Registrar y cobrar
                            </button>
                            <div class="dropdown-divider"></div>
                            <button target="blank" id="registrareimprimir" class="dropdown-item row-cursor-hand">
                                Registrar e imprimir
                            </button>
                            <div class="dropdown-divider"></div>
                            <button target="blank" id="registrarcobrareimprimir" class="dropdown-item row-cursor-hand">
                                Registrar, cobrar e imprimir
                            </button>

                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 py-2">
                        <input target="blank" id="imprimir" type="button" disabled value="Imprimir"
                               class="btn btn-info container-fluid" data-toggle="modal"
                               data-target="#modarprinttarget"/>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 py-2">
                        <input id="vercobros" type="button" disabled value="Cobros"
                               class="btn btn-success container-fluid" data-toggle="modal"
                               data-target="#modalMovimientos"/>
                    </div>
                </div>
                <div class="row btn-toolbar justify-content-center">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 py-2">
                        <input id="limpiar" type="button" value="Limpiar"
                               class="btn btn-light text-dark container-fluid"/>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 py-2">
                        <input id="modificar" type="button" disabled value="Modificar"
                               class="btn btn-warning text-dark container-fluid"/>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 py-2">
                        <input id="eliminar" type="button" disabled value="Eliminar"
                               class="btn btn-danger container-fluid"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Ventas registradas</h3>
            </div>
            <div class="card-body">
                <table id="recurso" class="table table-bordered dt-responsive table-hover row-cursor-hand"
                       style="width:100%">
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
    </div>

    {{--    Dialogo modal de los cobros --}}
    <div class="modal fade" id="modalMovimientos" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registro de cobros</h5>
                </div>
                <div class="modal-body">

                    <div class="container-fluid">
                        <div class="form-group row py-2">
                            <label class="col-form-label text-md-left">Id del movimiento:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">#</span>
                                </div>
                                <input id="idcobro" type="number" readonly="readonly"
                                       class="form-control"
                                       name="idcobro">
                            </div>
                        </div>
                        <div class="form-group row py-2">
                            <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-6 py-2">
                                <label class="col-form-label text-md-left">Valor total:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input readonly="readonly"
                                           class="form-control border-warning money"
                                           name="valor">
                                </div>
                            </div>
                            <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-6 py-2">
                                <label class="col-form-label text-md-left">Valor cobrado:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input id="valorcobrado" readonly="readonly"
                                           class="form-control border-success money"
                                           name="valorcobrado">
                                </div>
                            </div>
                            <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-6 py-2">
                                <label class="col-form-label text-md-left">Saldo:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input id="saldo" readonly="readonly"
                                           class="form-control border-danger money"
                                           name="saldo">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row py-2">
                            <label class="col-form-label text-md-left">Valor a cobrar en
                                efectivo:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">💵</span>
                                </div>
                                <input id="parteEfectiva"
                                       class="form-control money"
                                       name="parteEfectiva" required="required">
                            </div>
                        </div>

                        <div class="form-group row py-2">
                            <label class="col-form-label text-md-left">Valor con tarjeta:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">💳</span>
                                </div>
                                <input id="parteCrediticia"
                                       class="form-control money"
                                       name="parteCrediticia" required="required">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input id="cobrar" type="button" value="Cobrar"
                           class="btn btn-success container-fluid" data-dismiss="modal"/>
                    <input id="anularcobro" type="button" value="Reversar"
                           class="btn btn-danger container-fluid" data-dismiss="modal"/>
                    <input id="limpiarmodal" type="button" value="Limpiar"
                           class="btn btn-light container-fluid"/>
                </div>

                <div class="container-fluid">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 p-3">
                        <div class="card shadow mb-4 form-control">
                            <table id="cobros_table"
                                   class="table table-bordered dt-responsive table-hover row-cursor-hand"
                                   style="width:100%">
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input id="cerrarmodal" type="button" value="Cerrar"
                           class="btn btn-warning text-right" data-dismiss="modal"
                           data-target="#modalMovimientos"/>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal impresión -->
    <div class="modal fade" id="modarprinttarget" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">¿Cómo deseas imprimir?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="modal-body row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-6 col-sm-12">
                            <a id="imprimircarta" type="button" class="btn btn-light container-fluid"
                                    data-dismiss="modal" target="_blank">
                                <img class="img-fluid" src="{{ asset('assets/images/printer.png') }}"/>
                                <hr>
                                <p>Impresión tamaño carta</p>
                            </a>
                        </div>
                        <div class="col-xl-5 col-lg-6 col-md-6 col-sm-12">
                            <button id="imprimirpos" type="button" class="btn btn-light container-fluid"
                                    data-dismiss="modal">
                                <img class="img-fluid" src="{{ asset('assets/images/pos.png') }}"/>
                                <hr>
                                <p>Impresión POS</p>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar impresión</button>
                </div>
            </div>
        </div>
    </div>
    @include("js.ventas")
@endsection

