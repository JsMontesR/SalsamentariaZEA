<?php

namespace App\Jobs;

use App\Notifications\RecordatorioCuentaPorPagarNotification;
use App\Notifications\RecordatorioFacturaPorCobrarNotification;
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
        $ventas = Venta::query()
            ->whereNotNull('fechapago')
            ->whereNull('fechapagado')
            ->whereDate('fechapago', '<=', now()->toDateTimeString())
            ->whereRaw('DATEDIFF(NOW(),fechapago) <= ' . ActualizarNotificacionesYAlertas::DIASANTICIPACION)
            ->get();

        Log::info("Generando notificaciones de cuentas por cobrar");
        foreach ($ventas as $venta) {
            $empleadosANotificar = User::query()->where('rol_id', '<>', 3)->whereDoesntHave('notifications', function ($query) use ($venta) {
                $query->whereRaw("JSON_EXTRACT(data,'$.id') = " . $venta->id);
                $query->whereRaw('JSON_EXTRACT(data,"$.endpoint") = "' . ActualizarNotificacionesYAlertas::VENTA . '"');
                $query->whereRaw('JSON_EXTRACT(data,"$.tipo") = "' . ActualizarNotificacionesYAlertas::RECORDATORIO . '"');
            })->get();
            Notification::send($empleadosANotificar, new RecordatorioFacturaPorCobrarNotification($venta, "Recuerde cobrar al cliente " . $venta->cliente->name . " la venta #" . $venta->id . ", el saldo pendiente es de $" . number_format($venta->saldo, 0)));
        }
        Log::info("Notificaciones de cuentas por cobrar generadas satisfactoriamente");
    }
}
