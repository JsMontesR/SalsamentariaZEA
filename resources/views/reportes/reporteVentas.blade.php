@extends('layouts.app')

@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Reporte de ventas</h1>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
            </div>
            <div class="card-body">
                <form id="form1" name="form1">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Fecha inicio:</label>
                        <div class="col-md-8">
                            <input id="fechaInicio" type="date" class="form-control" value="{{old('fechaInicio')}}"
                                   name="fechaInicio" required autocomplete="fechaInicio">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-left">Fecha fin:</label>
                        <div class="col-md-8">
                            <input id="fechaFin" type="date" class="form-control" value="{{old('fechaFin')}}"
                                   name="fechaFin" required autocomplete="fechaFin">
                        </div>
                    </div>
                    <br>
                    <div class="row btn-toolbar justify-content-center">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 py-2">
                            <input id="filtrar" type="button" value="Filtrar"
                                   class="btn btn-primary container-fluid"/>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 py-2">
                            <input id="verimpresion" type="button" value="Ver impresión"
                                   class="btn btn-light text-dark container-fluid"/>
                        </div>
                    </div>
                </form>
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
@endsection

