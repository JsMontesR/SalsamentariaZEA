@extends('layouts.app')

@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Reporte de ventas</h1>
    </div>
    <br>
    <div class="container-fluid">
        @if(session()->has('error'))
            <div class="alert alert-warning" role="alert"><i class="fas fa-fw fa-exclamation-circle"></i> {{session('error')}}</div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
            </div>
            <div class="card-body">
                <form id="form" name="form">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Fecha inicio:</label>
                        <div class="col-md-8">
                            <input id="fechaInicio" type="date" class="form-control"
                                   name="fechaInicio" required autocomplete="fechaInicio">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Fecha fin:</label>
                        <div class="col-md-8">
                            <input id="fechaFin" type="date" class="form-control"
                                   name="fechaFin" required autocomplete="fechaFin">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Por cierre de caja (número de
                            cierre):</label>
                        <div class="col-md-8">
                            <input id="cierre_id" readonly
                                   class="form-control"
                                   name="cierre_id" required="required">
                        </div>
                    </div>
                    <div class="card mb-3 border border-dark">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="cierres"
                                       class="table table-bordered dt-responsive table-hover row-cursor-hand"
                                       style="width:100%">
                                    <tbody></tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="row btn-toolbar justify-content-center">
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 py-2">
                        <input id="aplicarFiltros" type="button" value="Aplicar filtros"
                               class="btn btn-success text-light container-fluid"/>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 py-2">
                        <input id="limpiar" type="button" value="Limpiar filtros"
                               class="btn btn-light container-fluid"/>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 py-2">
                        <input id="verimpresion" type="button" value="Ver impresión"
                               class="btn btn-primary text-light container-fluid"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Ventas realizadas</h3>
            </div>
            <div class="card-body">
                <table id="recurso" class="table table-bordered dt-responsive table-hover row-cursor-hand"
                       style="width:100%">
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    @include("js.reportes.reporteVentas")
@endsection

