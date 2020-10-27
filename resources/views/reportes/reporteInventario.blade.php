@extends('layouts.app')

@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Reporte de inventario</h1>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Opciones</h6>
            </div>
            <form id="form" name="form">
                <div class="card-body">
                    <div class="row btn-toolbar justify-content-center">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 py-2">
                            <input id="verimpresion" type="button" value="Ver impresión"
                                   class="btn btn-primary text-light container-fluid"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Productos en inventario</h3>
            </div>
            <div class="card-body">
                <table id="recurso" class="table table-bordered dt-responsive table-hover row-cursor-hand"
                       style="width:100%">
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    @include("js.reportes.reporteInventario")
@endsection

