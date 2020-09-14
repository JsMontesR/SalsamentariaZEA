<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Prophecy\Doubler\Generator\TypeHintReference;

class GenerarRecordatorios implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    const DIASANTICIPACION = 7;
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
        Log::info("Las notificaciones se generan con " . GenerarRecordatorios::DIASANTICIPACION . " días de anticipación");
        $usuarios = User::query()->where('rol_id', '<>', 3)->get();
        dispatch(new GenerarRecordatoriosPagoEntradas($usuarios));
        dispatch(new GenerarRecordatoriosCobroVentas($usuarios));
        dispatch(new GenerarRecordatoriosPagoNominas($usuarios));

    }
}
