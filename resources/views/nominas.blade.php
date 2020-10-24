@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Nóminas</h1>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Detalle de la nómina</h3>
            </div>
            <div class="card-body">
                <form id="form" name="form" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label id="label_id" class="col-md-4 col-form-label text-md-left">Id:</label>
                        <div class="col-md-8">
                            <input readonly="readonly" id="id" class="form-control"
                                   name="id">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Id del empleado:</label>
                        <div class="col-md-8">
                            <input id="empleado_id" class="form-control"
                                   name="empleado_id" required
                                   autocomplete="empleado_id">
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="empleados"
                                       class="table table-bordered dt-responsive table-hover row-cursor-hand"
                                       style="width:100%">
                                    <tbody></tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Nombre del empleado:</label>
                        <div class="col-md-8">
                            <input id="nombre" readonly="readonly"
                                   class="number form-control"
                                   name="nombre">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Documento de identidad:</label>
                        <div class="col-md-8">
                            <input id="di" readonly="readonly" class="number form-control"
                                   name="di">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Salario base:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-fw fa-money-bill-alt"></i></span>
                            </div>
                            <input id="salario" readonly="readonly"
                                   class="number form-control"
                                   name="salario">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Total a pagar:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input id="valor"
                                   class="number form-control"
                                   name="valor" required="required">
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
                </form>
                <br>
                <div class="row btn-toolbar justify-content-center">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 py-2 input-group-prepend be-addon">
                        <button id="registrar" tabindex="-1" type="button" class="btn btn-primary container-fluid">Registrar</button>
                        <button id="otherregister" tabindex="-1" data-toggle="dropdown" type="button"
                                class="btn btn-primary-link dropdown-toggle dropdown-toggle-split"
                                aria-expanded="false"><span
                                class="sr-only">Toggle Dropdown</span></button>
                        <div class="dropdown-menu" x-placement="top-start"
                             style="position: absolute; transform: translate3d(99px, -2px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <button id="registrarypagar" class="dropdown-item row-cursor-hand">Registrar y pagar</button>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 py-2">
                        <input id="imprimir" type="button" disabled value="Imprimir"
                               class="btn btn-info container-fluid"/>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 py-2">
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
                <h3 class="m-0 font-weight-bold text-primary text-center">Nóminas registradas</h3>
            </div>
            <div class="card-body">

                <table id="recurso" class="table table-bordered dt-responsive table-hover row-cursor-hand"
                       style="width:100%">
                    <tbody></tbody>
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
                                    <input id="valor" readonly="readonly"
                                           class="form-control border-warning number"
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
                                           class="form-control border-success number"
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
                                           class="form-control border-danger number"
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
                                       class="form-control number"
                                       name="parteEfectiva">
                            </div>
                        </div>

                        <div class="form-group row py-2">
                            <label class="col-form-label text-md-left">Valor con tarjeta:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">💳</span>
                                </div>
                                <input id="parteCrediticia"
                                       class="form-control number"
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
    @include("js.nominas")
@endsection

