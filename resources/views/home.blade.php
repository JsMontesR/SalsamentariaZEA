@extends('layouts.app')
@section('content')
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title text-center">Tablero de control</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{ route('ventas') }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Ventas</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg  bg-danger mt-1">
                                    <i class="fa fa-shopping-cart fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer text-light bg-danger clearfix small z-1">
                                <span class="float-left">Ver ventas</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <hr>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route('entradas')}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block">
                                    <h3 class="text-dark">Entradas</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg  bg-secondary mt-1">
                                    <i class="fa fa-truck fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-secondary text-light clearfix small z-1">
                                <span class="float-left">Ver entradas a inventario</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>

                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route("productos")}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Productos</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg bg-warning mt-1">
                                    <i class="fa fa-cube fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-warning text-dark clearfix small z-1">
                                <span class="float-left">Ver productos</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>

                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route("tiposproductos")}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h4 class="text-dark">Tipos de productos</h4>
                                </div>
                                <div class="float-right icon-circle-medium icon-box-lg  bg-success mt-1">
                                    <i class="fa fa-info-circle fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer text-light bg-success clearfix small z-1">
                                <span class="float-left">Ver tipos de productos</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route('clientes')}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Clientes</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg  bg-info mt-1">
                                    <i class="fa fa-users fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer text-light bg-info clearfix small z-1">
                                <span class="float-left">Ver clientes</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>

                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route('proveedores')}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Proveedores</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg  bg-primary mt-1">
                                    <i class="fa fa-people-carry fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer text-light bg-primary clearfix small z-1">
                                <span class="float-left">Ver proveedores</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route('empleados')}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Empleados</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg  bg-danger mt-1">
                                    <i class="fa fa-id-badge fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer text-light bg-danger clearfix small z-1">
                                <span class="float-left">Ver empleados</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>

                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route('nominas')}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Nómina</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg  bg-secondary mt-1">
                                    <i class="fa fa-hand-holding-usd fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer text-light bg-secondary clearfix small z-1"
                            >
                                <span class="float-left">Ver nómina</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route('movimientos')}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Movimientos</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg  bg-warning mt-1">
                                    <i class="fa fa-cogs fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer text-dark bg-warning clearfix small z-1"
                            >
                                <span class="float-left">Ver movimientos</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
