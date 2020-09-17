<?php

namespace App\Jobs;

use App\Entrada;
use App\Notifications\CuentaPorPagarNotification;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerarRecordatoriosPagoEntradas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $entradas = Entrada::query()->whereNull('fechapagado')->whereRaw('DATEDIFF(fechapago,NOW()) < ' . ActualizarNotificaciones::DIASANTICIPACION)->get();

        Log::info("Generando notificaciones de cuentas por pagar");
        foreach ($entradas as $entrada) {
            $empleadosANotificar = User::query()->where('rol_id', '<>', 3)->whereDoesntHave('notifications', function ($query) use ($entrada) {
                $query->whereRaw("JSON_EXTRACT(data,'$.id') = " . $entrada->id);
            })->get();
            Notification::send($empleadosANotificar, new CuentaPorPagarNotification($entrada, "Pagar al proveedor " . $entrada->proveedor->nombre . " la entrada #" . $entrada->id . ", el saldo pendiente es de $" . number_format($entrada->saldo, 0)));
        }
        Log::info("Notificaciones de cuentas por pagar generadas satisfactoriamente");
    }
}
