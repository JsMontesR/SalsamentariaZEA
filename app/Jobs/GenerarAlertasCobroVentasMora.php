<?php

namespace App\Jobs;

use App\Notifications\AlertaMoraFacturaPorCobrarNotification;
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

class GenerarAlertasCobroVentasMora implements ShouldQueue
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
            ->whereDate('fechapago', '>', now()->toDateTimeString())
            ->get();

        Log::info("Generando alertas de cuentas por cobrar en mora");
        foreach ($ventas as $venta) {
            $empleadosANotificar = User::query()->where('rol_id', '<>', 3)->whereDoesntHave('notifications', function ($query) use ($venta) {
                $query->whereRaw("JSON_EXTRACT(data,'$.id') = " . $venta->id);
                $query->whereRaw('JSON_EXTRACT(data,"$.endpoint") = "' . ActualizarNotificaciones::VENTA . '"');
                $query->whereRaw('JSON_EXTRACT(data,"$.tipo") = "' . ActualizarNotificaciones::ALERTA . '"');
            })->get();
            Notification::send($empleadosANotificar, new AlertaMoraFacturaPorCobrarNotification($venta, "La factura por cobrar para la venta #" . $venta->id . " a nombre del cliente " . $venta->cliente->name . " se encuentra en mora, el saldo pendiente es de $ " . number_format($venta->saldo, 0)));
        }
        Log::info("Alertas de cuentas por cobrar generadas satisfactoriamente");
    }
}
