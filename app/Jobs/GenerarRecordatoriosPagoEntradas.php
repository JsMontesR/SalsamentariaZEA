<?php

namespace App\Jobs;

use App\Entrada;
use App\Notifications\CuentaPorPagarNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerarRecordatoriosPagoEntradas implements ShouldQueue
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
        $entradas = Entrada::query()->whereNull('fechapagado')->whereRaw('DATEDIFF(fechapago,NOW()) = ' . GenerarRecordatorios::DIASANTICIPACION)->get();
        foreach ($entradas as $entrada) {
            foreach ($this->empleados as $empleado) {
                $empleado->notify(
                    new CuentaPorPagarNotification("Pagar al proveedor " . $entrada->proveedor->nombre . " la entrada #" . $entrada->id . ", el saldo pendiente es de $" . number_format($entrada->saldo, 0)));
            }
        }
    }
}
