@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Entradas a inventario</h1>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Detalle de la entrada</h3>
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
                        <label class="col-md-4 col-form-label text-md-left">Proveedor:</label>
                        <div class="col-md-8">
                            <input id="proveedor_id" readonly
                                   class="form-control"
                                   name="proveedor_id" required="required">
                        </div>
                    </div>
                    <div class="card mb-3 border border-dark">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="proveedores"
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
                                    <h3 class="m-0 font-weight-bold text-primary text-center">Productos de la
                                        entrada</h3>
                                </div>
                                <div id="card_productos_entrada_table"
                                     class="card-body">
                                    <table id="productos_entrada_table"
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
                            <input id="valor" type="number" readonly="readonly"
                                   class="money form-control"
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

                </form>
                <br>
                <div class="row btn-toolbar justify-content-center">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 py-2">
                        <input id="registrar" type="button" value="Registrar"
                               class="btn btn-primary container-fluid"/>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 py-2">
                        <input id="verpagos" type="button" disabled value="Pagos"
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
                <h3 class="m-0 font-weight-bold text-primary text-center">Entradas registradas</h3>
            </div>
            <div class="card-body">
                <table id="recurso" class="table table-bordered dt-responsive table-hover row-cursor-hand"
                       style="width:100%">
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
    </div>

    {{--    Dialogo modal de los pagos--}}
    <div class="modal fade" id="modalMovimientos" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registro de pagos</h5>
                </div>
                <div class="modal-body">

                    <div class="container-fluid">
                        <div class="form-group row py-2">
                            <label class="col-form-label text-md-left">Id del movimiento:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">#</span>
                                </div>
                                <input id="idpago" type="number" readonly="readonly"
                                       class="form-control"
                                       name="idpago">
                            </div>
                        </div>
                        <div class="form-group row py-2">
                            <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-6 py-2">
                                <label class="col-form-label text-md-left">Valor total:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input id="valor" type="number" readonly="readonly"
                                           class="money form-control border-warning"
                                           name="valor">
                                </div>
                            </div>
                            <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-6 py-2">
                                <label class="col-form-label text-md-left">Valor pagado:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input id="valorpagado" readonly="readonly"
                                           class="form-control border-success money"
                                           name="valorpagado">
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
                            <label class="col-form-label text-md-left">Valor a pagar en
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
                    <input id="pagar" type="button" value="Pagar"
                           class="btn btn-success container-fluid" data-dismiss="modal"/>
                    <input id="anularpago" type="button" value="Reversar"
                           class="btn btn-danger container-fluid" data-dismiss="modal"/>
                    <input id="limpiarmodal" type="button" value="Limpiar"
                           class="btn btn-light container-fluid"/>
                </div>

                <div class="container-fluid">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 p-3">
                        <div class="card shadow mb-4 form-control">
                            <table id="pagos_table"
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
    @include("js.entradas")
@endsection

