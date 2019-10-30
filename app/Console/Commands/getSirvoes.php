<?php

namespace App\Console\Commands;

use App\Area;
use App\Direccion;
use App\Director;
use App\Entidad;
use App\Estatus;
use App\Grupo;
use App\Institucion;
use App\Modalidad;
use App\MotivoRetiro;
use App\Municipio;
use App\Nivel;
use App\Plantel;
use App\Programa;
use App\Telefono;
use App\TipoRvoe;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class getSirvoes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:sirvoes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene los datos de programas de estudios de la base de datos de SIRVOES';

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

        $client = new \GuzzleHttp\Client();

        $consultaTotalesUrl = "https://www.sirvoes.sep.gob.mx/sirvoes/mvc/consultas/consultaTotales";

        $consultaTotalesResponse = $client->post($consultaTotalesUrl, [
            'json' => [
                'entidad' => null
            ]
        ]);

        $consultaTotales = json_decode($consultaTotalesResponse->getBody()->getContents());

        $consultaGeneralUrl = "https://www.sirvoes.sep.gob.mx/sirvoes/mvc/consultas/consultaGeneral";

        $consultaGeneralResponse = $client->post($consultaGeneralUrl, [
            'json' => [
                'indiceSuperior' => $consultaTotales->indiceInferior
            ]
        ]);

        $consultaGeneral = json_decode($consultaGeneralResponse->getBody()->getContents());

        foreach ($consultaGeneral as $index => $plantel) {
            $output->writeln("<info>[+] Plantel: {$index}</info>");

            $detallesInstitucionIdPlantelUrl = "https://www.sirvoes.sep.gob.mx/sirvoes/mvc/consultas/detallesInstitucionIdPlantel";

            $detallesInstitucionIdPlantelResponse = $client->post($detallesInstitucionIdPlantelUrl, [
                'json' => [
                    'idPlantel' => $plantel->idPlantel
                ]
            ]);

            $detallesInstitucionIdPlantel = json_decode($detallesInstitucionIdPlantelResponse->getBody()->getContents());

            /*$detallesTotalesUrl = "https://www.sirvoes.sep.gob.mx/sirvoes/mvc/consultas/detallesTotales";

            $detallesTotalesResponse = $client->post($detallesTotalesUrl, [
                'json' => [
                    'idPlantel' => $plantel->idPlantel,
                    'indiceSuperior' => 999999
                ]
            ]);

            $detallesTotales = json_decode($detallesTotalesResponse->getBody()->getContents());*/

            $detallesIdPlantelUrl = "https://www.sirvoes.sep.gob.mx/sirvoes/mvc/consultas/detallesIdPlantel";

            $detallesIdPlantelResponse = $client->post($detallesIdPlantelUrl, [
                'json' => [
                    'idPlantel' => $plantel->idPlantel,
                    'indiceSuperior' => 999999 // $detallesTotales->indiceInferior
                ]
            ]);

            $detallesIdPlantel = json_decode($detallesIdPlantelResponse->getBody()->getContents());
            
            foreach ($detallesIdPlantel as $datosPrograma) {
                $motivoRetiro = null;

                if (isset($datosPrograma->fkIdMotivoRetiro)) {
                    $motivoRetiro = MotivoRetiro::where('descripcion', $datosPrograma->fkIdMotivoRetiro->descripcionRetiro)->first();

                    if (!$motivoRetiro) {
                        $motivoRetiro = MotivoRetiro::create([
                            'descripcion' => $datosPrograma->fkIdMotivoRetiro->descripcionRetiro,
                        ]);
                    }
                }

                $estatus = null;

                if (isset($datosPrograma->fkIdEstatus)) {
                    $estatus = Estatus::where('nombre', $datosPrograma->fkIdEstatus->nombreEstatus)->first();

                    if (!$estatus) {
                        $estatus = Estatus::create([
                            'nombre' => $datosPrograma->fkIdEstatus->nombreEstatus,
                        ]);
                    }
                }

                $modalidad = null;

                if (isset($datosPrograma->fkIdModalidad)) {
                    $modalidad = Modalidad::where('nombre', $datosPrograma->fkIdModalidad->nombreModalidad)->first();

                    if (!$modalidad) {
                        $modalidad = Modalidad::create([
                            'nombre' => $datosPrograma->fkIdModalidad->nombreModalidad,
                        ]);
                    }
                }

                $area = null;

                if (isset($datosPrograma->fkIdArea)) {
                    $area = Area::where('nombre', $datosPrograma->fkIdArea->nombreArea)->first();

                    if (!$area) {
                        $area = Area::create([
                            'nombre' => $datosPrograma->fkIdArea->nombreArea,
                            'descripcion' => $datosPrograma->fkIdArea->descripcionArea,
                        ]);
                    }
                }

                $nivel = null;

                if (isset($datosPrograma->fkIdNivel)) {
                    $nivel = Nivel::where('nombre', $datosPrograma->fkIdNivel->nombreNivel)->first();

                    if (!$nivel) {
                        $nivel = Nivel::create([
                            'nombre' => $datosPrograma->fkIdNivel->nombreNivel,
                            'descripcion' => $datosPrograma->fkIdNivel->descripcionNivel,
                        ]);
                    }
                }

                $plantel = null;

                if (isset($datosPrograma->fkIdPlantel)) {
                    $telefono = null;

                    if (isset($datosPrograma->fkIdPlantel->fkIdTelefono)) {
                        $telefono = Telefono::where('numero', $datosPrograma->fkIdPlantel->fkIdTelefono->telefono)
                            ->where('lada', $datosPrograma->fkIdPlantel->fkIdTelefono->lada)
                            ->where('extension', $datosPrograma->fkIdPlantel->fkIdTelefono->extension)
                            ->first();

                        if (!$telefono) {
                            $telefono = Telefono::create([
                                'numero' => $datosPrograma->fkIdPlantel->fkIdTelefono->telefono,
                                'lada' => $datosPrograma->fkIdPlantel->fkIdTelefono->lada,
                                'extension' => $datosPrograma->fkIdPlantel->fkIdTelefono->extension,
                            ]);
                        }
                    }

                    $director = null;

                    if (isset($datosPrograma->fkIdPlantel->fkIdDirector)) {
                        $director = Director::where('nombre', $datosPrograma->fkIdPlantel->fkIdDirector->nombre)
                        ->where('apellido_paterno', $datosPrograma->fkIdPlantel->fkIdDirector->apellidoPaterno)
                        ->where('apellido_materno', $datosPrograma->fkIdPlantel->fkIdDirector->apellidoMaterno)
                        ->where('correo_electronico', $datosPrograma->fkIdPlantel->fkIdDirector->correoElect)
                        ->first();

                        if (!$director) {
                            $director = Director::create([
                                'nombre' => $datosPrograma->fkIdPlantel->fkIdDirector->nombre,
                                'apellido_paterno' => $datosPrograma->fkIdPlantel->fkIdDirector->apellidoPaterno,
                                'apellido_materno' => $datosPrograma->fkIdPlantel->fkIdDirector->apellidoMaterno,
                                'correo_electronico' => $datosPrograma->fkIdPlantel->fkIdDirector->correoElect,
                                'datos_personales_id' => $datosPrograma->fkIdPlantel->fkIdDirector->idDatosPersonales,
                            ]);
                        }
                    }

                    $direccion = null;

                    if (isset($datosPrograma->fkIdPlantel->fkIdDireccion)) {
                        $municipio = null;

                        if (isset($datosPrograma->fkIdPlantel->fkIdDireccion->fkIdMunicipio)) {
                            $entidad = null;

                            if (isset($datosPrograma->fkIdPlantel->fkIdDireccion->fkIdMunicipio->fkIdEntidad)) {
                                $entidad = Entidad::where('nombre', $datosPrograma->fkIdPlantel->fkIdDireccion->fkIdMunicipio->fkIdEntidad->nombreEntidad)->first();

                                if (!$entidad) {
                                    $entidad = Entidad::create([
                                        'nombre' => $datosPrograma->fkIdPlantel->fkIdDireccion->fkIdMunicipio->fkIdEntidad->nombreEntidad,
                                    ]);
                                }
                            }

                            $municipio = Municipio::where('nombre', $datosPrograma->fkIdPlantel->fkIdDireccion->fkIdMunicipio->nombreMunicipio)
                                ->where('entidad_id', $entidad->id)
                                ->first();

                            if (!$municipio) {
                                $municipio = Municipio::create([
                                    'nombre' => $datosPrograma->fkIdPlantel->fkIdDireccion->fkIdMunicipio->nombreMunicipio,
                                    'entidad_id' => $entidad->id, 
                                ]);
                            }
                        }

                        $direccion = Direccion::where('calle', $datosPrograma->fkIdPlantel->fkIdDireccion->calle)
                        ->where('codigo_postal', $datosPrograma->fkIdPlantel->fkIdDireccion->codigoPostal)
                        ->where('municipio_id', $municipio->id)
                        ->first();

                        if (!$direccion) {
                            $direccion = Direccion::create([
                                'calle' => $datosPrograma->fkIdPlantel->fkIdDireccion->calle,
                                'codigo_postal' => $datosPrograma->fkIdPlantel->fkIdDireccion->codigoPostal,
                                'municipio_id' => $municipio->id, 
                            ]);
                        }
                    }

                    $institucion = null;

                    if (isset($datosPrograma->fkIdPlantel->fkIdInstitucion)) {
                        $tipoRvoe = null;

                        if (isset($datosPrograma->fkIdPlantel->fkIdInstitucion->fkIdTipoRvoe)) {
                            $tipoRvoe = TipoRvoe::where('nombre', $datosPrograma->fkIdPlantel->fkIdInstitucion->fkIdTipoRvoe->nombreRvoe)->first();
        
                            if (!$tipoRvoe) {
                                $tipoRvoe = TipoRvoe::create([
                                    'nombre' => $datosPrograma->fkIdPlantel->fkIdInstitucion->fkIdTipoRvoe->nombreRvoe,
                                ]);
                            }
                        }

                        $grupo = null;

                        if (isset($datosPrograma->fkIdPlantel->fkIdInstitucion->fkIdGrupo)) {
                            $grupo = Grupo::where('nombre', $datosPrograma->fkIdPlantel->fkIdInstitucion->fkIdGrupo->nombreGrupo)->first();
        
                            if (!$grupo) {
                                $grupo = Grupo::create([
                                    'nombre' => $datosPrograma->fkIdPlantel->fkIdInstitucion->fkIdGrupo->nombreGrupo,
                                ]);
                            } 
                        }

                        $institucion = Institucion::where('nombre', $datosPrograma->fkIdPlantel->fkIdInstitucion->nombreInstitucion)
                            ->where('tipo_rvoe_id', $tipoRvoe->id)
                            ->first();

                        if (!$institucion) {
                            $institucion = Institucion::create([
                                'nombre' => $datosPrograma->fkIdPlantel->fkIdInstitucion->nombreInstitucion,
                                'razon_social' => $datosPrograma->fkIdPlantel->fkIdInstitucion->razonSocial,
                                'autorizada_equivalencias' => $datosPrograma->fkIdPlantel->fkIdInstitucion->autorizadaEquivalencias,
                                'fecha_aut_revalidacion_equivalencia' => $datosPrograma->fkIdPlantel->fkIdInstitucion->fecAutRevEquiv,
                                'fecha_rev_revalidacion_equivalencia' => $datosPrograma->fkIdPlantel->fkIdInstitucion->fecRevRevEquiv,
                                'grupo_id' => $grupo->id, 
                                'tipo_rvoe_id' => $tipoRvoe->id, 
                            ]);
                        }
                    }

                    $plantel = Plantel::where('nombre', $datosPrograma->fkIdPlantel->nombrePlantel)
                    ->where('institucion_id', $institucion->id)
                    ->first();

                    if (!$plantel) {
                        $plantel = Plantel::create([
                            'nombre' => $datosPrograma->fkIdPlantel->nombrePlantel,
                            'excelencia' => $datosPrograma->fkIdPlantel->excelencia,
                            'correo_electronico' => $datosPrograma->fkIdPlantel->correoElect,
                            'pagina_web' => $datosPrograma->fkIdPlantel->paginaWeb,
                            'institucion_id' => $institucion->id,
                            'direccion_id' => isset($direccion) ? $direccion->id : null, 
                            'director_id' => isset($director) ? $director->id : null,
                            'telefono_id' => isset($telefono) ? $telefono->id : null, 
                        ]);
                    }
                }
                $programa = Programa::where('nombre', $datosPrograma->nombrePrograma)
                    ->where('llave', $datosPrograma->llave)
                    ->where('llave', $datosPrograma->acuerdo)
                    ->where('fecha_otorgamiento_rvoe', $datosPrograma->fecOtorRvoe)
                    ->where('plantel_id', $plantel->id)
                    ->first();

                if (!$programa) {
                    $programa = Programa::create([
                        'nombre' => $datosPrograma->nombrePrograma,
                        'llave' => $datosPrograma->llave,
                        'acuerdo' => $datosPrograma->acuerdo,
                        'fecha_otorgamiento_rvoe' => $datosPrograma->fecOtorRvoe,
                        'fecha_retiro_rvoe' => $institucion->fecRetiroRvoe,
                        'plantel_id' => $plantel->id, 
                        'nivel_id' => $nivel->id, 
                        'area_id' => $area->id, 
                        'modalidad_id' => $modalidad->id, 
                        'estatus_id' => $estatus->id, 
                        'motivo_retiro_id' => $motivoRetiro->id, 
                    ]);
                }
            }
        }

        return 'ok';
    }
}
