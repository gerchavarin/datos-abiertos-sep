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
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class excelGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'excel:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera archivo Xlsx con los datos de los programas de estudio de SIRVOES';

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
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()
            ->setCreator(config('app.name'))
            ->setTitle('SIRVOES')
            ->setSubject("Programas de estudio")
            ->setDescription("Fecha: " . date('m/d/Y h:i:s a', time()));

        $spreadsheet->removeSheetByIndex(0);

        // Crear la hoja de PROGRAMAS DE ESTUDIO

        $myWorkSheet = new Worksheet($spreadsheet, 'PROGRAMAS DE ESTUDIO');
        $spreadsheet->addSheet($myWorkSheet, 0);

        // Formatear la hoja de PROGRAMAS DE ESTUDIO

        $worksheet = $spreadsheet->getSheetByName('PROGRAMAS DE ESTUDIO');

        $worksheet->getStyle('A1:AD1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('92D050');

        $worksheet->getCell('A1')->setValue('TIPO DE RVOE O ACUERDO');
        $worksheet->getCell('B1')->setValue('ESTATUS DEL RVOE O ACUERDO');
        $worksheet->getCell('C1')->setValue('NOMBRE DE LA INSTITUCIÓN');
        $worksheet->getCell('D1')->setValue('RAZÓN SOCIAL');
        $worksheet->getCell('E1')->setValue('INSITUCIÓN AUTORIZADA PARA OTORGAR REVALIDACIONES Y EQUIVALENCIAS');
        $worksheet->getCell('F1')->setValue('FECHA DE AUTORIZACIÓN PARA OTORGAR REVALIDACIONES Y EQUIVALENCIAS');
        $worksheet->getCell('G1')->setValue('FECHA DE REVOCACIÓN PARA OTORGAR REVALIDACIONES Y EQUIVALENCIAS');
        $worksheet->getCell('H1')->setValue('GRUPOS DEL PROGRAMA DE MEJORA AL QUE PERTENECE LA INSTITUCIÓN');
        $worksheet->getCell('I1')->setValue('NOMBRE DEL CAMPUS O PLANTEL');
        $worksheet->getCell('J1')->setValue('EXCELENCIA');
        $worksheet->getCell('K1')->setValue('ESTADO');
        $worksheet->getCell('L1')->setValue('MUNICIPIO O ALCALDÍA');
        $worksheet->getCell('M1')->setValue('DOMICILIO');
        $worksheet->getCell('N1')->setValue('CÓDIGO POSTAL');
        $worksheet->getCell('O1')->setValue('AMPLIACIÓN');
        $worksheet->getCell('P1')->setValue('CORREO ELECTRÓNICO');
        $worksheet->getCell('Q1')->setValue('PÁGINA WEB');
        $worksheet->getCell('R1')->setValue('TELÉFONO');
        $worksheet->getCell('S1')->setValue('LADA');
        $worksheet->getCell('T1')->setValue('EXTENSIÓN');
        $worksheet->getCell('U1')->setValue('CORREO ELECTRÓNICO DEL DIRECTOR');
        $worksheet->getCell('V1')->setValue('LLAVE DEL PROGRAMA DE ESTUDIOS');
        $worksheet->getCell('W1')->setValue('NIVEL DE ESTUDIOS');
        $worksheet->getCell('X1')->setValue('NOMBRE DEL PROGRAMA DE ESTUDIOS');
        $worksheet->getCell('Y1')->setValue('ÁREA DE ESTUDIOS');
        $worksheet->getCell('Z1')->setValue('MODALIDAD DE ESTUDIOS');
        $worksheet->getCell('AA1')->setValue('NÚMERO DE RVOE O ACUERDO');
        $worksheet->getCell('AB1')->setValue('FECHA DE OTORGAMIENTO DEL RVOE');
        $worksheet->getCell('AC1')->setValue('FECHA DE RETIRO DEL RVOE');
        $worksheet->getCell('AD1')->setValue('MOTIVO DE RETIRO DEL RVOE');

        $worksheet->getStyle('E')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $worksheet->getStyle('F')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $worksheet->getStyle('H')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $worksheet->getStyle('I')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $worksheet->getStyle('J')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $worksheet->getStyle('K')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $worksheet->getStyle('L')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $worksheet->getStyle('M')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $worksheet->getStyle('N')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $worksheet->getStyle('O')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $worksheet->getStyle('P')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $worksheet->getStyle('Q')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        // Rellena datos de los programas de estudio

        $programas = Programa::all();

        $count = 2;

        foreach ($programas as $programa) {
            $plantel = Plantel::find($programa->plantel_id);
            $institucion = Institucion::find($plantel->institucion_id);
            $grupo = Grupo::find($institucion->grupo_id);
            $tipoRvoe = TipoRvoe::find($institucion->tipo_rvoe_id);
            $direccion = Direccion::find($plantel->direccion_id);
            $municipio = Municipio::find($direccion->municipio_id);
            $entidad = Entidad::find($municipio->entidad_id);
            $director = Director::find($plantel->director_id);
            $telefono = Telefono::find($plantel->telefono_id);
            $nivel = Nivel::find($programa->nivel_id);
            $area = Area::find($programa->area_id);
            $modalidad = Modalidad::find($programa->modalidad_id);
            $estatus = Estatus::find($programa->estatus_id);
            $motivoRetiro = MotivoRetiro::find($programa->motivo_retiro_id);

            $worksheet->getCell('A' . $count)->setValue($tipoRvoe->nombre);
            $worksheet->getCell('B' . $count)->setValue($estatus->nombre);
            $worksheet->getCell('C' . $count)->setValue($institucion->nombre);
            $worksheet->getCell('D' . $count)->setValue($institucion->razon_social);
            $worksheet->getCell('E' . $count)->setValue($institucion->autorizada_equivalencias);
            $worksheet->getCell('F' . $count)->setValue($institucion->fecha_aut_revalidacion_equivalencia);
            $worksheet->getCell('G' . $count)->setValue($institucion->fecha_rev_revalidacion_equivalencia);
            $worksheet->getCell('H' . $count)->setValue($grupo->nombre);
            $worksheet->getCell('I' . $count)->setValue($plantel->nombre);
            $worksheet->getCell('J' . $count)->setValue($plantel->excelencia);
            $worksheet->getCell('K' . $count)->setValue($entidad->nombre);
            $worksheet->getCell('L' . $count)->setValue($municipio->nombre);
            $worksheet->getCell('M' . $count)->setValue($direccion->calle);
            $worksheet->getCell('N' . $count)->setValue($direccion->codigo_postal);
            $worksheet->getCell('O' . $count)->setValue($direccion->ampliacion);
            $worksheet->getCell('P' . $count)->setValue($plantel->correo_electronico);
            $worksheet->getCell('Q' . $count)->setValue($plantel->pagina_web);
            $worksheet->getCell('R' . $count)->setValue(isset($telefono->numero) ? $telefono->numero : null);
            $worksheet->getCell('S' . $count)->setValue(isset($telefono->lada) ? $telefono->lada : null);
            $worksheet->getCell('T' . $count)->setValue(isset($telefono->extension) ? $telefono->extension : null);
            $worksheet->getCell('U' . $count)->setValue(isset($director->correo_electronico) ? $director->correo_electronico : null);
            $worksheet->getCell('V' . $count)->setValue($programa->llave);
            $worksheet->getCell('W' . $count)->setValue($nivel->descripcion);
            $worksheet->getCell('X' . $count)->setValue($programa->nombre);
            $worksheet->getCell('Y' . $count)->setValue($area->descripcion);
            $worksheet->getCell('Z' . $count)->setValue($modalidad->nombre);
            $worksheet->getCell('AA' . $count)->setValue($programa->acuerdo);
            $worksheet->getCell('AB' . $count)->setValue($programa->fecha_otorgamiento_rvoe);
            $worksheet->getCell('AC' . $count)->setValue($programa->fecha_retiro_rvoe);
            $worksheet->getCell('AD' . $count)->setValue($motivoRetiro->descripcion);
            $count++;
        }

        // Redimensiona las columnas de la hoja de PROGRAMAS DE ESTUDIO

        foreach (range('A', 'AD') as $col) {
            $worksheet->getColumnDimension($col)->setAutoSize(true);  
        }

        $worksheet->freezePane('A2');

        $worksheet->setAutoFilter( $worksheet->calculateWorksheetDimension() );

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        $writer->save('programas.xlsx');
    }
}
