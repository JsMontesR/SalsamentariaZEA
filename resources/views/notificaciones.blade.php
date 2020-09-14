@extends('layouts.app')
@section('content')
    <div class="container-fluid dashboard-content">
        <div class="card">
            <h5 class="card-header">Registro de notificaciones</h5>
            <div class="card-body">
                <div class="list-group">
                    @forelse(Auth::user()->notifications as $notification)
                        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 text-dark">Nueva notificación</h5>
                                <small> {{ $notification->created_at }}</small>
                            </div>
                            <p class="mb-1"> {{ $notification->data }}</p>
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
