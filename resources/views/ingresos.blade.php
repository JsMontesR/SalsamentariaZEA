@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Ingresos</h1>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Detalle del ingreso</h3>
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
                                    <h3 class="m-0 font-weight-bold text-primary text-center">Productos del ingreso</h3>
                                </div>
                                <div id="card_productos_carrito_table"
                                     class="card-body">
                                    <table id="productos_ingreso_table"
                                           class="table table-bordered dt-responsive wrap table-hover"
                                           style="width:100%">
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Dinero a ingresar a la caja:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-prepend">
                                <span class="input-group-text">💵</span>
                            </div>
                            <input id="valor"
                                   class="form-control money"
                                   name="valor" required autocomplete="valor">
                        </div>
                    </div>
                </form>
                <br>
                <div class="row btn-toolbar justify-content-center">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 py-2">
                        <input id="registrar" type="button" value="Ingresar"
                               class="btn btn-primary container-fluid"/>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 py-2">
                        <input id="limpiar" type="button" value="Limpiar"
                               class="btn btn-light text-dark container-fluid"/>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 py-2">
                        <input id="eliminar" type="button" disabled value="Reversar"
                               class="btn btn-danger container-fluid"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Ingresos registrados</h3>
            </div>
            <div class="card-body">
                <table id="recurso" class="table table-bordered dt-responsive table-hover row-cursor-hand"
                       style="width:100%">
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
    </div>
    @include("js.ingresos")
@endsection

