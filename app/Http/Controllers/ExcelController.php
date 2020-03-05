<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\mdlChecadas;
use App\mdlPersonal;
use App\mdlHorarios;
use Carbon\Carbon;
use Redirect;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ExcelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {         
       \Excel::create('LaravelExcel', function($excel){
           $excel->sheet('Checadas', function($sheet){
               $checadas = mdlChecadas::all();
               $sheet->fromArray($checadas);
           });
       })->download('xlsx');
    }

    /**
     * Obtiene un reporte completo
     * de un maestro.
     * $idMaestro: ID del maestro al que se le quiera obtener un reporte
     */
    public function getReporteMaestro($idMaestro)
    {
        if($idMaestro != null && mdlPersonal::where('id', '=', $idMaestro)->exists() &&  mdlChecadas::where('id_tblPersonal', '=', $idMaestro)->exists()){
           //Obtiene el nombre de empleado y fecha actual
           $nombreMaestro = mdlPersonal::select('nombre')->where('id', '=', $idMaestro)->pluck('nombre');
           //Obtiene la fecha actual
           $fechaActual = Carbon::now()->format('y-m-d');
           \Excel::create('REPORTEDEASISTENCIA_' . str_replace(']','',str_replace('[','',$nombreMaestro)) . '_' . $fechaActual, function($excel) use($idMaestro, $nombreMaestro, $fechaActual){
               $excel->sheet('Checadas', function($sheet) use($idMaestro, $nombreMaestro, $fechaActual){
                   //$sheet->fromArray($checadas);
                   $fechaAntigua = mdlChecadas::where('id_tblPersonal', '=', $idMaestro)->orderBy('fecha', 'ASC')->first();
                   $fechaReciente = mdlChecadas::where('id_tblPersonal', '=', $idMaestro)->orderBy('fecha', 'DESC')->first();
                   $checadas = mdlChecadas::select('id_tblPersonal AS IDMAESTRO', 'hora AS ENTRADA', 'hora_salida AS SALIDA', 'checada AS TIPODECHECADA', 'comentario AS COMENTARIO', 'fecha AS FECHA')->where('id_tblPersonal', '=', $idMaestro)->get();                
                   $horarios = $this->getHorario($idMaestro);
                   //Información de universidad y maestro
                   $sheet->row(1, ['UNIVERSIDAD ESTATAL DE SONORA']);
                   $sheet->row(2, ['UNIDAD ACADEMICA: HERMOSILLO']);
                   $sheet->row(4, ['REGISTRO DE ASISTENCIA']);
                   $sheet->row(5, ['SISTEMA: UESVIRTUAL']);
                   $sheet->row(6, ['PERIODO DEL: ', $fechaAntigua->fecha, ' A ', $fechaReciente->fecha]);
                   $sheet->row(8, ['NOMBRE DEL MAESTRO: ']);
                   $sheet->row(9, [$this->clean($nombreMaestro)]);
                   //Horarios
                   $sheet->row(11, ['HORARIO']);
                   $row = 12;
                   foreach($horarios as $horario) {
                       $sheet->row($row, [$horario->DIA, $horario->HORA_ENTRADA, $horario->HORA_SALIDA]);
                       $row = $row + 1;
                   }
                   //Checadas
                   $row2 = $row + 2;
                   $sheet->row($row2, ['DIA', 'FECHA', 'ENTRADA', 'SALIDA']);
                   $weekMap = [
                    0 => 'DOMINGO',
                    1 => 'LUNES',
                    2 => 'MARTES',
                    3 => 'MIERCOLES',
                    4 => 'JUEVES',
                    5 => 'VIERNES',
                    6 => 'SABADO',];
                   foreach($checadas as $checada){
                       $row2 = $row2 + 1;
                       $sheet->row($row2, [$weekMap[$this->getWeekDay($checada->FECHA)], $checada->FECHA, $checada->ENTRADA, $checada->SALIDA]);
                   }

               });
           })->download('xlsx');
        }
        else {
            //redirect
        }
    }

    /**
     * Obtiene un reporte de un maestro
     * entre las fechas dadas
     * $idMaestro: ID del maestro al que se le quiera obtener un reporte
     * $fechaInicial: fecha mas antigua
     * $fechaFinal: fecha mas actual
     * Formato de fecha: yyyy-mm-dd
     */
    public function getReporteMaestroFechas(Request $request)
    {
        $idMaestro = $request->id;
        $fechaInicial = $request->fechaInicio;
        $fechaFinal = $request->fechaFinal;
        
        if($idMaestro != null && mdlPersonal::where('id', '=', $idMaestro)->exists() &&  mdlChecadas::where('id_tblPersonal', '=', $idMaestro)->exists()){
            //Obtiene el nombre de empleado y fecha actual
            $nombreMaestro = mdlPersonal::select('nombre')->where('id', '=', $idMaestro)->pluck('nombre');
            //Obtiene la fecha actual
            $fechaActual = Carbon::now()->format('y-m-d');
            \Excel::create('REPORTEDEASISTENCIA_' . str_replace(']','',str_replace('[','',$nombreMaestro)) . '_' . $fechaActual, function($excel) use($idMaestro, $nombreMaestro, $fechaActual, $fechaInicial, $fechaFinal){
                $excel->sheet('Checadas', function($sheet) use($idMaestro, $nombreMaestro, $fechaActual,$fechaInicial, $fechaFinal){
                    //$sheet->fromArray($checadas);
                    $checadas = mdlChecadas::select('id_tblPersonal AS IDMAESTRO', 'hora AS ENTRADA', 'hora_salida AS SALIDA', 'checada AS TIPODECHECADA', 'comentario AS COMENTARIO', 'fecha AS FECHA', 'checada as CHECADA_ENTRADA', 'checada_salida as CHECADA_SALIDA')->where('id_tblPersonal', '=', $idMaestro)->whereBetween('fecha', array($fechaInicial, $fechaFinal))->get();
                    $horarios = $this->getHorario($idMaestro);
                    //Información de universidad y maestro
                    $sheet->row(1, ['UNIVERSIDAD ESTATAL DE SONORA']);
                    $sheet->row(2, ['UNIDAD ACADEMICA: HERMOSILLO']);
                    $sheet->row(4, ['REGISTRO DE ASISTENCIA']);
                    $sheet->row(5, ['SISTEMA: UESVIRTUAL']);
                    $sheet->row(6, ['PERIODO DEL: ', $fechaInicial, ' A ', $fechaFinal]);
                    $sheet->row(8, ['NOMBRE DEL MAESTRO: ']);
                    $sheet->row(9, [$this->clean($nombreMaestro)]);

                    //Horarios
                    $sheet->row(11, ['HORARIO']);
                    $row = 12;
                    foreach($horarios as $horario) {
                        $sheet->row($row, [$horario->DIA, $horario->HORA_ENTRADA, $horario->HORA_SALIDA]);
                        $row = $row + 1;
                    }

                    //Checadas
                    $row2 = $row + 2;
                    $sheet->row($row2, ['DIA', 'FECHA', 'ENTRADA', 'SALIDA', 'OBSERVACIONES_ENTRADA', 'OBSERVACIONES_SALIDA']);
                    $weekMap = [
                        0 => 'DOMINGO',
                        1 => 'LUNES',
                        2 => 'MARTES',
                        3 => 'MIERCOLES',
                        4 => 'JUEVES',
                        5 => 'VIERNES',
                        6 => 'SABADO',
                    ];

                    foreach($checadas as $checada){
                        $row2 = $row2 + 1;
                        $sheet->row($row2, [$weekMap[$this->getWeekDay($checada->FECHA)], $checada->FECHA, $checada->ENTRADA, $checada->SALIDA, $this->getTipoChecadaEntrada((string) $checada->CHECADA_ENTRADA), $this->getTipoChecadaSalida((string) $checada->CHECADA_SALIDA)]);
                    }
                });
            })->download('xlsx');
         }
         else {
             //redirect
         }
    }

    /**
     * Obtiene un reporte de los ultimos dieciseis dias
     * $idMaestro: ID del maestro al que se le quiera obtener un reporte
     */
   public function getReporteQuincenal($idMaestro)
   {
       if($idMaestro != null && mdlPersonal::where('id', '=', $idMaestro)->exists() &&  mdlChecadas::where('id_tblPersonal', '=', $idMaestro)->exists()){
           //Get nombre de empleado y fecha actual
           $nombreMaestro = (string)mdlPersonal::select('nombre')->where('id', '=', $idMaestro)->pluck('nombre');
           $fechaActual = Carbon::now()->format('Y-m-d');
           $fechaQuincenal = Carbon::now()->subDays(16); //fecha 16 dias antes de la fecha actual.
           \Excel::create('REPORTEDEASISTENCIA_QUINCENAL_' . str_replace(']','',str_replace('[','',$nombreMaestro)) . '_' . $fechaActual, function($excel) use($idMaestro, $nombreMaestro, $fechaActual, $fechaQuincenal){
               $excel->sheet('Checadas', function($sheet) use($idMaestro, $fechaActual, $fechaQuincenal, $nombreMaestro){
                    $checadas = mdlChecadas::select('id_tblPersonal AS IDMAESTRO', 'hora AS ENTRADA', 'hora_salida AS SALIDA', 'checada AS TIPODECHECADA', 'comentario AS COMENTARIO', 'fecha AS FECHA', 'checada as CHECADA_ENTRADA', 'checada_salida as CHECADA_SALIDA')->where('id_tblPersonal', '=', $idMaestro)->whereBetween('fecha', array($fechaQuincenal->toDateString(), $fechaActual))->get();
                    $horarios = $this->getHorario($idMaestro);
                    //Información de universidad y maestro
                    $sheet->row(1, ['UNIVERSIDAD ESTATAL DE SONORA']);
                    $sheet->row(2, ['UNIDAD ACADEMICA: HERMOSILLO']);
                    $sheet->row(4, ['REGISTRO DE ASISTENCIA']);
                    $sheet->row(5, ['SISTEMA: UESVIRTUAL']);
                    $sheet->row(6, ['PERIODO DEL: ', $fechaQuincenal->toDateString(), ' A ', $fechaActual]);
                    $sheet->row(8, ['NOMBRE DEL MAESTRO: ']);
                    $sheet->row(9, [$this->clean($nombreMaestro)]);
                    //Horarios
                    $sheet->row(11, ['HORARIO']);
                    $row = 12;
                    foreach($horarios as $horario) {
                        $sheet->row($row, [$horario->DIA, $horario->HORA_ENTRADA, $horario->HORA_SALIDA]);
                        $row = $row + 1;
                    }
                    //Checadas
                    $row2 = $row + 2;
                    $sheet->row($row2, ['DIA', 'FECHA', 'ENTRADA', 'SALIDA', 'OBSERVACIONES_ENTRADA', 'OBSERVACIONES_SALIDA']);

                    $weekMap = [
                     0 => 'DOMINGO',
                     1 => 'LUNES',
                     2 => 'MARTES',
                     3 => 'MIERCOLES',
                     4 => 'JUEVES',
                     5 => 'VIERNES',
                     6 => 'SABADO',];
                    
                    foreach($checadas as $checada){
                        $row2 = $row2 + 1;
                        $sheet->row($row2, [$weekMap[$this->getWeekDay($checada->FECHA)], $checada->FECHA, $checada->ENTRADA, $checada->SALIDA, $this->getTipoChecadaEntrada((string) $checada->CHECADA_ENTRADA), $this->getTipoChecadaSalida((string) $checada->CHECADA_SALIDA)]);
                    }


               });
           })->download('xlsx');
       }
       else {
           //redirect
       }
   }

    /**
     * Regresa la descripcion según el tipo de checada de entraa.
     * Recibe como parametro un valor de checada(0- 11).
     * @param $checada número de tipo string
     */
    public function getTipoChecadaEntrada($checada){
        $observaciones = [
            "0" => "CON BONO",
            "1" => "RETARDO",
            "2" => "RETARDO",
            "3" => "Inasistencia",
            "4" => "Incapacidad",
            "5" => "Omision",
            "6" => "CANJE TIEMPO EXTRA",
            "7" => "DIA ECONOMICO",
            "8" => "OMISION",
            "9" => "SALIDA",
            "10" => "SALIDA ANTICIPADA",
            "11" => "PERMISO POR HORA ",
        ];
        
        foreach($observaciones as $key => $value){
            if($key == $checada) {
                return $value;
            }
        }
    }

    /**
     * Regresa la descripcion según el tipo de checada de entraa.
     * Recibe como parametro un valor de checada(1, 2, 5).
     * @param $checada número de tipo string
     */
    public function getTipoChecadaSalida($checada){
        $observaciones = [
            "1" => "SALIDA NORMAL",
            "2" => "SALIDA ANTICIPADA",
            "5" => "OMISION",
            "8" => "OMISION",
        ];

        foreach($observaciones as $key => $value){
            if($key == $checada) {
                return $value;
            }
        }
    }

   /**
     * Obtiene un reporte de los ultimos 7 dias
     * $idMaestro: ID del maestro al que se le quiera obtener un reporte
     */
    public function getReporteSemanal($idMaestro)
    {
        if($idMaestro != null && mdlPersonal::where('id', '=', $idMaestro)->exists() &&  mdlChecadas::where('id_tblPersonal', '=', $idMaestro)->exists()){
            //Get nombre de empleado y fecha actual
            $nombreMaestro = (string)mdlPersonal::select('nombre')->where('id', '=', $idMaestro)->pluck('nombre');
            $fechaActual = Carbon::now()->format('Y-m-d');
            $fechaSemanal = Carbon::now()->subDays(8); //fecha 8 dias antes de la fecha actual.
            \Excel::create('REPORTEDEASISTENCIA_SEMANAL_' . str_replace(']','',str_replace('[','',$nombreMaestro)) . '_' . $fechaActual, function($excel) use($idMaestro, $nombreMaestro, $fechaActual, $fechaSemanal){
                $excel->sheet('Checadas', function($sheet) use($idMaestro, $fechaActual, $fechaSemanal, $nombreMaestro){
                    $checadas = mdlChecadas::select('id_tblPersonal AS IDPERSONAL', 'fecha AS FECHA', 'hora AS ENTRADA', 'hora_salida AS SALIDA', 'checada AS TIPODECHECADA', 'comentario AS COMENTARIO', 'fecha AS FECHA', 'checada as CHECADA_ENTRADA', 'checada_salida as CHECADA_SALIDA')->where('id_tblPersonal', '=', $idMaestro)->whereBetween('fecha', array($fechaSemanal->toDateString(), $fechaActual))->get();                
                    $horarios = $this->getHorario($idMaestro);
                    //Información de universidad y maestro
                    $sheet->row(1, ['UNIVERSIDAD ESTATAL DE SONORA']);
                    $sheet->row(2, ['UNIDAD ACADEMICA: HERMOSILLO']);
                    $sheet->row(4, ['REGISTRO DE ASISTENCIA']);
                    $sheet->row(5, ['SISTEMA: UESVIRTUAL']);
                    $sheet->row(6, ['PERIODO DEL: ', $fechaSemanal->toDateString(), ' A ', $fechaActual]);
                    $sheet->row(8, ['NOMBRE DEL MAESTRO: ']);
                    $sheet->row(9, [$this->clean($nombreMaestro)]);
                    //Horarios
                    $sheet->row(11, ['HORARIO']);
                    $row = 12;
                    foreach($horarios as $horario) {
                        $sheet->row($row, [$horario->DIA, $horario->HORA_ENTRADA, $horario->HORA_SALIDA]);
                        $row = $row + 1;
                    }
                    //Checadas
                    $row2 = $row + 2;
                    $sheet->row($row2, ['DIA', 'FECHA', 'ENTRADA', 'SALIDA', 'OBSERVACIONES_ENTRADA', 'OBSERVACIONES_SALIDA']);
                    $weekMap = [
                     0 => 'DOMINGO',
                     1 => 'LUNES',
                     2 => 'MARTES',
                     3 => 'MIERCOLES',
                     4 => 'JUEVES',
                     5 => 'VIERNES',
                     6 => 'SABADO',];
                    foreach($checadas as $checada){
                        $row2 = $row2 + 1;
                        $sheet->row($row2, [$weekMap[$this->getWeekDay($checada->FECHA)], $checada->FECHA, $checada->ENTRADA, $checada->SALIDA, $this->getTipoChecadaEntrada((string) $checada->CHECADA_ENTRADA), $this->getTipoChecadaSalida((string) $checada->CHECADA_SALIDA)]);
                    }
                });
            })->download('xlsx');
        }
        else {
            //redirect
        }
    }

    /**
     * Obtiene un reporte con todos los maestros registrados
     */
    public function getPersonal(){
        $fechaActual = Carbon::now()->format('d-m-y');
        \Excel::create('REPORTEDEMAESTROS_' . $fechaActual, function($excel) {
            $excel->sheet('MAESTROS', function($sheet) {
                $personal = mdlPersonal::all()->get();
                $sheet->fromArray($personal);
            });
        })->download('xlsx');
    }

    /**
     * Obtiene horario de un maestro seleccionado
     * @param $idMaestro
     */
    public function getHorario($idMaestro){
        $horarios = mdlHorarios::select('dia AS DIA', 'hora_entrada AS HORA_ENTRADA', 'hora_salida AS HORA_SALIDA')->where('id_tblPersonal', '=', $idMaestro)->get();        
        foreach ($horarios as $horario) {
            if($horario->DIA == 1){
                $horario->DIA = "LUNES";
            }
            else if($horario->DIA == 2){
                $horario->DIA = "MARTES";
            }
            else if($horario->DIA == 3){
                $horario->DIA = "MIERCOLES";
            }
            else if($horario->DIA == 4){
                $horario->DIA = "JUEVES";
            }
            else if($horario->DIA == 5){
                $horario->DIA = "VIERNES";
            }
            else if($horario->DIA == 6){
                $horario->DIA = "SABADO";
            }
            else if($horario->DIA == 7){
                $horario->DIA = "DOMINGO";
            }
        }
        
        return $horarios;
    }

    /**
     * Reporte general
     * Obtiene un reporte general de checadas de todos los maestros activos
     * Metodo no funcional... aún
     */
    function getReporteGeneral(Request $request){
        //$idMaestroActivos solo obtiene ids de maestros activos
        $fechaInicial = $request->fechaInicio;
        $fechaFinal = $request->fechaFinal;
        $idMaestrosActivos = mdlPersonal::where('modulo', '!=', 1)->get();
        $fechaActual = Carbon::now()->format('Y-m-d');
        
        if($idMaestrosActivos != null){
            \Excel::create('REPORTEDEASISTENCIA_GENERAL', function($excel) use($fechaActual){
                $idMaestrosActivos = mdlPersonal::where('modulo', '!=', 1)->get();
                foreach($idMaestrosActivos as $idMaestro){
                    $excel->sheet((string)mdlPersonal::select('nombre')->where('id', '=', $idMaestro)->pluck('nombre'), function($sheet) use($idMaestro){
                        $checadas = mdlChecadas::select('id_tblPersonal AS IDMAESTRO', 'hora AS ENTRADA', 'hora_salida AS SALIDA', 'checada AS TIPODECHECADA', 'comentario AS COMENTARIO', 'fecha AS FECHA', 'checada as CHECADA_ENTRADA', 'checada_salida as CHECADA_SALIDA')->where('id_tblPersonal', '=', $idMaestro)->get();
                        $horarios = $this->getHorario($idMaestro);

                        //Información de universidad y maestro
                        $sheet->row(1, ['UNIVERSIDAD ESTATAL DE SONORA']);
                        $sheet->row(2, ['UNIDAD ACADEMICA: HERMOSILLO']);
                        $sheet->row(4, ['REGISTRO DE ASISTENCIA']);
                        $sheet->row(5, ['SISTEMA: UESVIRTUAL']);
                        $sheet->row(6, ['PERIODO DEL: ', $fechaQuincenal->toDateString(), ' A ', $fechaActual]);
                        $sheet->row(8, ['NOMBRE DEL MAESTRO: ']);
                        $sheet->row(9, [$this->clean($nombreMaestro)]);
                        //Horarios
                        $sheet->row(11, ['HORARIO']);
                        $row = 12;
                        foreach($horarios as $horario) {
                            $sheet->row($row, [$horario->DIA, $horario->HORA_ENTRADA, $horario->HORA_SALIDA]);
                            $row = $row + 1;
                        }

                        //Checadas
                        $row2 = $row + 2;
                        $sheet->row($row2, ['DIA', 'FECHA', 'ENTRADA', 'SALIDA', 'OBSERVACIONES_ENTRADA', 'OBSERVACIONES_SALIDA']);

                        $weekMap = [
                        0 => 'DOMINGO',
                        1 => 'LUNES',
                        2 => 'MARTES',
                        3 => 'MIERCOLES',
                        4 => 'JUEVES',
                        5 => 'VIERNES',
                        6 => 'SABADO',];
                        
                        foreach($checadas as $checada){
                            $row2 = $row2 + 1;
                            $sheet->row($row2, [$weekMap[$this->getWeekDay($checada->FECHA)], $checada->FECHA, $checada->ENTRADA, $checada->SALIDA, $this->getTipoChecada((string) 1), $this->getTipoChecada((string) $checada->CHECADA_SALIDA)]);
                        }
                    });
                }
            })->download('xlsx');
        }
    }

    /**
     * Funcion que ayuda a obtener dia de la semana
     * Recibe como parametro una fecha y regresi su dia de la semana
     */
    function getWeekDay($date) {
        return date('w', strtotime($date));
    }

    /**
     * Funcion que ayuda a remover caracteres especiales
     */
    function clean($string) {
        //$string = str_replace(' ', '_', $string); // Remplaza todos los espacios con guiones.
        return trim(preg_replace('/[^A-Za-z0-9\-]/', ' ', $string)); // Elimina caracteres especiales y los reemplaza por un espacio.
     }



     public function getReporteGeneralFechas(Request $request)
    {
        $idMaestro = $request->id;
        $fechaInicial = $request->fechaInicio;
        $fechaFinal = $request->fechaFinal;
        
        if($idMaestro != null && mdlPersonal::where('id', '=', $idMaestro)->exists() &&  mdlChecadas::where('id_tblPersonal', '=', $idMaestro)->exists()){
            //Obtiene el nombre de empleado y fecha actual
            $nombreMaestro = mdlPersonal::select('nombre')->where('id', '=', $idMaestro)->pluck('nombre');
            //Obtiene la fecha actual
            $fechaActual = Carbon::now()->format('y-m-d');
            \Excel::create('REPORTEDEASISTENCIA_' . str_replace(']','',str_replace('[','',$nombreMaestro)) . '_' . $fechaActual, function($excel) use($idMaestro, $nombreMaestro, $fechaActual, $fechaInicial, $fechaFinal){
                $excel->sheet('Checadas', function($sheet) use($idMaestro, $nombreMaestro, $fechaActual,$fechaInicial, $fechaFinal){
                    //$sheet->fromArray($checadas);
                    $checadas = mdlChecadas::select('id_tblPersonal AS IDMAESTRO', 'hora AS ENTRADA', 'hora_salida AS SALIDA', 'checada AS TIPODECHECADA', 'comentario AS COMENTARIO', 'fecha AS FECHA', 'checada as CHECADA_ENTRADA', 'checada_salida as CHECADA_SALIDA')->where('id_tblPersonal', '=', $idMaestro)->whereBetween('fecha', array($fechaInicial, $fechaFinal))->get();
                    $horarios = $this->getHorario($idMaestro);
                    //Información de universidad y maestro
                    $sheet->row(1, ['UNIVERSIDAD ESTATAL DE SONORA']);
                    $sheet->row(2, ['UNIDAD ACADEMICA: HERMOSILLO']);
                    $sheet->row(4, ['REGISTRO DE ASISTENCIA']);
                    $sheet->row(5, ['SISTEMA: UESVIRTUAL']);
                    $sheet->row(6, ['PERIODO DEL: ', $fechaInicial, ' A ', $fechaFinal]);
                    $sheet->row(8, ['NOMBRE DEL MAESTRO: ']);
                    $sheet->row(9, [$this->clean($nombreMaestro)]);

                    //Horarios
                    $sheet->row(11, ['HORARIO']);
                    $row = 12;
                    foreach($horarios as $horario) {
                        $sheet->row($row, [$horario->DIA, $horario->HORA_ENTRADA, $horario->HORA_SALIDA]);
                        $row = $row + 1;
                    }

                    //Checadas
                    $row2 = $row + 2;
                    $sheet->row($row2, ['DIA', 'FECHA', 'ENTRADA', 'SALIDA', 'OBSERVACIONES_ENTRADA', 'OBSERVACIONES_SALIDA']);
                    $weekMap = [
                        0 => 'DOMINGO',
                        1 => 'LUNES',
                        2 => 'MARTES',
                        3 => 'MIERCOLES',
                        4 => 'JUEVES',
                        5 => 'VIERNES',
                        6 => 'SABADO',
                    ];

                    foreach($checadas as $checada){
                        $row2 = $row2 + 1;
                        $sheet->row($row2, [$weekMap[$this->getWeekDay($checada->FECHA)], $checada->FECHA, $checada->ENTRADA, $checada->SALIDA, $this->getTipoChecadaEntrada((string) $checada->CHECADA_ENTRADA), $this->getTipoChecadaSalida((string) $checada->CHECADA_SALIDA)]);
                    }
                });
            })->download('xlsx');
         }
         else {
             //redirect
         }
    }



}
