<?php

namespace App\Jobs;

use App\Notifications\RecordatorioCuentaPorPagarNotification;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LimpiarNotificacionesAntiguas implements ShouldQueue
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
        $empleadosANotificar = User::where('rol_id', '<>', 3)->get();
        foreach ($empleadosANotificar as $empleadoANotificar) {
            $empleadoANotificar->notifications()->whereRaw('DATEDIFF(NOW(),created_at) >= ' . ActualizarNotificacionesYAlertas::DIASDEVENCIMIENTO)->delete();
        }
    }
}
