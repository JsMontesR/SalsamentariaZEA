<?php

namespace App\Jobs;

use App\Entrada;
use App\Notifications\AlertaMoraCuentaPorPagarNotification;
use App\Notifications\RecordatorioCuentaPorPagarNotification;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerarAlertasPagoEntradasMora implements ShouldQueue
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
        $entradas = Entrada::query()
            ->whereNotNull('fechapago')
            ->whereNull('fechapagado')
            ->whereDate('fechapago', '>', now()->toDateTimeString())
            ->get();

        Log::info("Generando alertas de cuentas por pagar en mora");
        foreach ($entradas as $entrada) {
            $empleadosANotificar = User::query()->where('rol_id', '<>', 3)->whereDoesntHave('notifications', function ($query) use ($entrada) {
                $query->whereRaw("JSON_EXTRACT(data,'$.id') = " . $entrada->id);
                $query->whereRaw('JSON_EXTRACT(data,"$.endpoint") = "' . ActualizarNotificacionesYAlertas::ENTRADA . '"');
                $query->whereRaw('JSON_EXTRACT(data,"$.tipo") = "' . ActualizarNotificacionesYAlertas::ALERTA . '"');
            })->get();
            Notification::send($empleadosANotificar, new AlertaMoraCuentaPorPagarNotification($entrada, "La cuenta por pagar para la entrada #" . $entrada->id . " con el proveedor " . $entrada->proveedor->nombre . " se encuentra en mora, el saldo pendiente es de $ " . number_format($entrada->saldo, 0)));
        }
        Log::info("Alertas de cuentas por pagar generadas satisfactoriamente");
    }
}
