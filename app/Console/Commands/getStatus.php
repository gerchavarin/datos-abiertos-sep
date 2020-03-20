<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\StatusInstitucion;

class getStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene el estatus de la implementacion de titulación electrónica en el país';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $client = new \GuzzleHttp\Client(['verify'=>false]);


        for($i=1;$i<33;$i++){

            $count = 0;
            $estado = '';
            $url = "https://siurp.sep.gob.mx/mvc/consultaInstituciones/institucionPorEntidad?entidadId={$i}&_=1579567637978";
    
            $statuses = $client->get($url, [
    
            ]);
            $statuses = json_decode($statuses->getBody()->getContents());
    
            foreach ($statuses as $status){
                if($status->descripcionStatus != 'YA EMITE TÍTULOS ELECTRÓNICOS'){
                    //$output->writeln("<info>[+]{$status->nombreInstitucion}-{$status->descripcionEntidad}-{$status->descripcionStatus}</info>");
                    $count+=1;
                    $estado = $status->descripcionEntidad;

                    StatusInstitucion::create(['institucion_id' => $status->institucionId,
                                                'status_id' => $status->statusId,
                                                'sostenimiento_id' => $status->sostenimientoId,
                                                'nombre_institucion' => $status->nombreInstitucion,
                                                'entidad_id' => $status->entidadId,
                                                'descripcion_entidad' => $status->descripcionEntidad,
                                                'descripcion_status' => $status->descripcionStatus]
                                            );
                }
            }

            $output->writeln("<info>[+]{$count} prospectos en {$estado}</info>");    
        }        
    }
}
