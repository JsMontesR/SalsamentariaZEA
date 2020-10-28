@extends('layouts.app')
@section('content')
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title text-center">Reportes</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route("reporteVentas")}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Reporte de ventas</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg bg-warning mt-1">
                                    <i class="fa fa-clipboard-check fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-warning text-dark clearfix small z-1">
                                <span class="float-left">Ver reporte de ventas</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>

                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route("reporteInventario")}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h4 class="text-dark">Reporte de inventario</h4>
                                </div>
                                <div class="float-right icon-circle-medium icon-box-lg  bg-success mt-1">
                                    <i class="fa fa-box-open fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer text-light bg-success clearfix small z-1">
                                <span class="float-left">Ver reporte de inventario</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route('reporteBalance')}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Balance</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg  bg-info mt-1">
                                    <i class="fa fa-balance-scale fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer text-light bg-info clearfix small z-1">
                                <span class="float-left">Ver balances</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>

                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route('reporteFacturasPorCobrar')}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Facturas por cobrar</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg  bg-primary mt-1">
                                    <i class="fa fa-donate fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer text-light bg-primary clearfix small z-1">
                                <span class="float-left">Ver facturas por cobrar</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route('reporteCuentasPorPagar')}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Cuentas por pagar</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg  bg-danger mt-1">
                                    <i class="fa fa-money-bill-alt fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer text-light bg-danger clearfix small z-1">
                                <span class="float-left">Ver cuentas por pagar</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>

                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route('reporteProductosMasVendidos')}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Productos más vendidos</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg  bg-secondary mt-1">
                                    <i class="fa fa-cart-arrow-down fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer text-light bg-secondary clearfix small z-1"
                            >
                                <span class="float-left">Ver productos menos vendidos</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <a href="{{route("reporteClientesQueMasCompran")}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-inline-block ">
                                    <h3 class="text-dark">Clientes que más compran</h3>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg bg-warning mt-1">
                                    <i class="fa fa-meh fa-fw fa-sm text-light"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-warning text-dark clearfix small z-1">
                                <span class="float-left">Ver reporte de clientes que menos compran</span>
                                <span class="float-right">
                  <em class="fas fa-angle-right"></em>
                </span>
                            </div>
                        </div>
                    </a>

                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card border-3 border-top border-top-primary">
                        <div class="card-body">
                            <h5 class="text-muted">Saldo en caja</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">{{ $dineroEnCaja }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
