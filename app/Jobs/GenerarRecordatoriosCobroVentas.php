<?php

namespace App\Jobs;

use App\Notifications\CuentaPorPagarNotification;
use App\User;
use App\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class GenerarRecordatoriosCobroVentas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ventas = Venta::query()->whereNull('fechapagado')->whereRaw('DATEDIFF(fechapago,NOW()) < ' . ActualizarNotificaciones::DIASANTICIPACION)->get();

        Log::info("Generando notificaciones de cuentas por cobrar");
        foreach ($ventas as $venta) {
            $empleadosANotificar = User::query()->where('rol_id', '<>', 3)->whereDoesntHave('notifications', function ($query) use ($venta) {
                $query->whereRaw("JSON_EXTRACT(data,'$.id') = " . $venta->id);
            })->get();
            Notification::send($empleadosANotificar, new CuentaPorPagarNotification($venta, "Cobrar al cliente " . $venta->cliente->name . " la venta #" . $venta->id . ", el saldo pendiente es de $" . number_format($venta->saldo, 0)));
        }
        Log::info("Notificaciones de cuentas por cobrar generadas satisfactoriamente");
    }
}
