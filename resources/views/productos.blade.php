@extends('layouts.app')
@section('content')
    <div class="card-header py-3">
        <h1 class="m-0 font-weight-bold text-primary text-center">Productos</h1>
    </div>
    <br>

    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Detalle del producto</h3>
            </div>
            <div class="card-body">
                <form id="form" name="form" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label id="label_id" class="col-md-4 col-form-label text-md-left">Id:</label>
                        <div class="col-md-8">
                            <input readonly="readonly" id="id" class="form-control" name="id">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id="label_nombre" class="col-md-4 col-form-label text-md-left">Nombre:</label>

                        <div class="col-md-8">
                            <input id="nombre" class="form-control"
                                   name="nombre" required autocomplete="nombre">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id="label_categoria" class="col-md-4 col-form-label text-md-left">Categoría de
                            producto</label>

                        <div class="col-md-8">
                            <select id="categoria" name="categoria"
                                    class="form-control"
                                    name="categoria" required>
                                <option disabled selected value>Seleccione una opción</option>
                                <option value="Unitario">Unitario</option>
                                <option value="Granel">Granel</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id="label_tipo_id" class="col-md-4 col-form-label text-md-left">Tipo de
                            producto</label>

                        <div class="col-md-8">
                            <select id="tipo_id" name="tipo_id"
                                    class="form-control" required>
                                <option disabled selected value>Seleccione una opción</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{$tipo->Id}}">{{$tipo->Nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id="label_costo" class="col-md-4 col-form-label text-md-left">Costo por
                            unidad/kg:</label>

                        <div class="col-md-8">
                            <input id="costo" type="number" class="form-control"
                                   value="{{old('costo')}}" name="costo" required autocomplete="costo">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id="label_utilidad" class="col-md-4 col-form-label text-md-left">Utilidad por
                            unidad/kg:</label>

                        <div class="col-md-8">
                            <input id="utilidad" type="number"
                                   class="form-control"
                                   name="utilidad" required autocomplete="utilidad">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id="label_precio" class="col-md-4 col-form-label text-md-left">Precio por
                            unidad/kg:</label>
                        <div class="col-md-8">
                            <input id="precio" type="number" class="form-control"
                                   name="precio" required autocomplete="precio">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id="label_stock" class="col-md-4 col-form-label text-md-left">Unidades/g en
                            stock:</label>
                        <div class="col-md-8">
                            <input id="stock" type="number" class="form-control"
                                   value="{{old('stock')}}" name="stock" required autocomplete="stock">
                        </div>
                    </div>
                </form>
                <br>
                <div class="row btn-toolbar justify-content-center">

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="registrar" type="button" value="Registrar"
                               class="btn btn-primary container-fluid"/>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="limpiar" type="button" value="Limpiar"
                               class="btn btn-light text-dark container-fluid"/>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="modificar" type="button" value="Modificar"
                               class="btn btn-warning container-fluid"/>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 py-2">
                        <input id="eliminar" type="button" value="Eliminar" class="btn btn-danger container-fluid"/>
                    </div>

                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Productos unitarios registrados</h3>
            </div>
            <div class="card-body">
                <table id="recurso" class="table table-bordered dt-responsive table-hover row-cursor-hand"
                       style="width:100%">
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/libs/js/controllers/productos.js') }}"></script>
@endsection

