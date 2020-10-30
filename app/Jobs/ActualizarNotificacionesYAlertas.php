<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ActualizarNotificacionesYAlertas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const DIASANTICIPACION = 7;
    const DIASDEVENCIMIENTO = 40;

    /*
     * Definición de tipos de modelos asociados a una notifiación
     */
    const ENTRADA = "entradas";
    const VENTA = "ventas";
    const NOMINA = "nomina";

    /*
    * Definición de tipos de notificación
    */
    const RECORDATORIO = "recordatorio";
    const ALERTA = "alerta";

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
        Log::info("Generando notificaciones a usuarios");
        Log::info("Las notificaciones se generan con " . ActualizarNotificacionesYAlertas::DIASANTICIPACION . " días de anticipación");
        dispatch(new GenerarRecordatoriosPagoEntradas());
        dispatch(new GenerarRecordatoriosCobroVentas());
        dispatch(new GenerarAlertasCobroVentasMora());
        dispatch(new GenerarAlertasPagoEntradasMora());
//        dispatch(new GenerarRecordatoriosPagoNominas($usuarios));
        dispatch(new LimpiarNotificacionesAntiguas());
    }
}
