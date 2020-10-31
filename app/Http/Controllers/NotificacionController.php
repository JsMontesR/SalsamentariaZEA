<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notificaciones = Auth::user()->unreadNotifications;
        return view("notificaciones", compact('notificaciones'));
    }

    /**
     *
     * Marca una notificación como leida
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function marcarNotificacionComoLeida(Request $request)
    {
        DatabaseNotification::find($request->id)->markAsRead();
        return back()->with('success', 'Notificación marcada como leida');
    }
}
