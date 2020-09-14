<?php

namespace App\Jobs;

use App\Nomina;
use App\Notifications\NominaPorPagarNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerarRecordatoriosPagoNominas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $empleados;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($empleados)
    {
        $this->empleados = $empleados;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

//        $nominas = Nomina::query()->whereNull('fechapagado')->whereRaw('DATEDIFF(fechapago,NOW()) = ' . $dias)->get();
//        foreach ($nominas as $nomina) {
//            foreach ($this->empleados as $empleado) {
//                $empleado->notify(
//                    new NominaPorPagarNotification("Recuerde pagar al empleado " . $entrada->proveedor->nombre . " la entrada #" . $entrada->id . ", el saldo pendiente es de $" . number_format($entrada->saldo, 0)));
//            }
//            Log::info($entrada);
//        }
    }
}
