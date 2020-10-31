@extends('layouts.app')
@section('content')
    <div class="container-fluid dashboard-content">
        <div class="card">
            <h5 class="card-header">Registro de notificaciones</h5>
            <div class="card-body">
                <div class="list-group">
                    @forelse($notificaciones as $unreadNotificacion)
                        <a class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                @if($unreadNotificacion->data["endpoint"] == "entradas")
                                    <h5 class="mb-1 text-dark">Notificación de pago de factura</h5>
                                @elseif($unreadNotificacion->data["endpoint"] == "ventas")
                                    <h5 class="mb-1 text-dark">Notificación de cobro de factura</h5>
                                @elseif($unreadNotificacion->data["endpoint"] == "nominas")
                                    <h5 class="mb-1 text-dark">Notificación de pago de nómina</h5>
                                @endif
                                <small> {{ $unreadNotificacion->created_at }}</small>
                                <form method="POST" action="{{route('marcarNotificacionComoLeida',$unreadNotificacion->id)}}"
                                      class="pull-right">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-danger btn-xs">X</button>
                                </form>
                            </div>
                            <p class="mb-1"> {{ $unreadNotificacion->data["mensaje"] }}</p>
                        </a>
                    @empty
                        <a class="list-group-item flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 text-dark">No hay notificaciones disponibles</h5>
                            </div>
                            <p class="mb-1"> Inténtalo más tarde</p>
                        </a>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
