<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/circular-std/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icon-css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/css/select.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/toastr.min.css') }}">
    <script src="{{ asset('assets/vendor/jquery/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('assets/vendor/slimscroll/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets/libs/js/main-js.js') }}"></script>
    <script src="{{ asset('assets/libs/js/spanish.js') }}"></script>
    <script src="{{ asset('assets/libs/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/libs/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/js/jquery.mask.min.js') }}"></script>

    <link rel="shortcut icon" href="{{asset('favicon.png')}}">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body>
<!-- ============================================================== -->
<!-- main wrapper -->
<!-- ============================================================== -->
<div class="dashboard-main-wrapper">
    <!-- ============================================================== -->
    <!-- navbar -->
    <!-- ============================================================== -->
    <div class="dashboard-header">
        <nav class="navbar navbar-expand-lg bg-white fixed-top">
            <a class="navbar-brand" href="{{route('home')}}">{{config('app.name', 'Laravel')}}</a>
            <button class="navbar-toggler px-4" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon d-inline"><i class="fas fa-caret-square-down mr-2"></i></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto navbar-right-top">
                    <li class="nav-item dropdown nav-user" s>
                        <a class="nav-link dropdown-toggle" role="button"
                           aria-haspopup="true" aria-expanded="false"
                           id="navbarDropdownMenuLink2">{{ Auth::user()->name }}</a>
                    </li>
                    <li class="nav-item dropdown notification">
                        <a class="nav-link nav-icons" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-fw fa-bell"></i>
                            @if(Auth::user()->notifications->count() > 0)
                                <span class="indicator"></span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right notification-dropdown">
                            <li>
                                <div class="notification-title font-bold">Notificaciones</div>
                                <div class="notification-list">
                                    <div class="list-group">
                                        @forelse(Auth::user()->notifications as $notification)
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div
                                                    class="notification-list-user-img float-left icon-circle-medium icon-box-md mt-1">
                                                    <i class="fa fa-exclamation-circle fa-fw"></i>
                                                </div>
                                                <div class="notification-info">
                                                    <div class="notification-list-user-block">
                                                        {{ $notification->data["mensaje"] }}
                                                        <div
                                                            class="notification-date">{{$notification->created_at}}</div>
                                                    </div>
                                                </div>
                                            </a>
                                        @empty
                                            <a class="list-group-item">
                                                <div class="notification-info">
                                                    <div class="notification-list-user-block">
                                                        No hay nada pendiente hasta el momento.
                                                    </div>
                                                </div>
                                            </a>
                                        @endforelse
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="{{ route('notificaciones') }}">
                                    <div class="list-footer">Ver todas las notificaciones
                                    </div>
                                </a>

                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown nav-user">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false"
                           id="navbarDropdownMenuLink2"><i class="fas fa-fw fa-cog"></i></a>
                        <div class="dropdown-menu dropdown-menu-right nav-user-dropdown"
                             aria-labelledby="navbarDropdownMenuLink2">
                            <a id="cerrarcaja" class="dropdown-item text-center" href="#">
                                <i class="fas fa-history mr-2"></i>Cerrar caja
                            </a>
                            <a class="dropdown-item text-center" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="fas fa-power-off mr-2"></i>{{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>

            </div>
        </nav>
    </div>

    <div class="nav-left-sidebar sidebar-dark">
        <div class="menu-list">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="d-xl-none d-lg-none" href="{{route('home')}}}">Tablero de control</a>
                <button class="navbar-toggler mx-2" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ventas') }}"><i class="fas fa-fw fa-shopping-cart"></i>Ventas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('entradas')}}"><i class="fas fa-fw fa-truck"></i>Entradas
                                a inventario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route("productos")}}"><i class="fas fa-fw fa-cubes"></i>Productos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('tiposproductos')}}"><i
                                    class="fas fa-fw fa-info-circle"></i>Tipos de productos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('clientes')}}"><i class="fas fa-fw fa-users"></i>Clientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('proveedores')}}"><i
                                    class="fas fa-fw fa-people-carry"></i>Proveedores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('empleados')}}"><i class="fas fa-fw fa-id-badge"></i>Empleados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('nominas')}}"><i
                                    class="fas fa-fw fa-hand-holding-usd"></i>Nómina</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('retiros') }}"><i class="fas fa-fw fa-sign-out-alt"></i>Retiros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ingresos') }}"><i class="fas fa-fw fa-sign-in-alt"></i>Ingresos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('servicios') }}"><i
                                    class="fas fa-fw fa-calculator"></i>Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tiposservicios') }}"><i
                                    class="fas fa-fw fa-bolt"></i>Tipos de servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('movimientos') }}"><i class="fas fa-fw fa-cogs"></i>Movimientos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reportes') }}"><i class="fab fa-fw fa-wpforms"></i>Reportes</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div class="dashboard-wrapper">
        @yield('content')
        <div class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div align="center" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        Campo Virtual Software © 2020. Todos los derechos reservados.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script>
    $(document).ready(function () {
        $("#cerrarcaja").click(function () {
            $.ajax({
                url: "/api/cierres/generarCierre",
                type: "get",
                success: function (data) {
                    swal("¡Operación exitosa!", data.msg, "success");
                },
                error: function (err) {
                    $.each(err.responseJSON.errors, function (i, error) {
                        swal("Ha ocurrido un error", error[0], "error");
                    });
                    console.error(err);
                }
            })
        });
    });
</script>
