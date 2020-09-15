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
                                   class="form-control"
                                   name="nombre">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Documento de identidad:</label>
                        <div class="col-md-8">
                            <input id="di" readonly="readonly" class="form-control"
                                   name="di">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Salario base:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-fw fa-money-bill-alt"></i></span>
                            </div>
                            <input id="salario" type="number" readonly="readonly"
                                   class="form-control"
                                   name="salario">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Valor a pagar con efectivo:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-prepend">
                                <span class="input-group-text">💵</span>
                            </div>
                            <input id="parteEfectiva" type="number"
                                   class="form-control"
                                   name="parteEfectiva" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Valor a pagar con tarjeta:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-prepend">
                                <span class="input-group-text">💳</span>
                            </div>
                            <input id="parteCrediticia" type="number"
                                   class="form-control"
                                   name="parteCrediticia" required="required">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Total pago:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input id="valor" type="number" readonly
                                   class="form-control"
                                   name="valor" required="required">
                        </div>
                    </div>
                </form>
                <br>
                <div class="row btn-toolbar justify-content-center">

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="pagar" type="button" value="Efectuar pago"
                               class="btn btn-success container-fluid"/>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="limpiar" type="button" value="Limpiar"
                               class="btn btn-light text-dark container-fluid"/>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="eliminar" type="button" value="Deshacer pago" class="btn btn-danger container-fluid"/>
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
    @include("js.nominas")
@endsection

