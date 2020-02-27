<?php

namespace App\Http\Controllers;

use App\mdlChecadas;
use App\mdlPersonal;
use App\mdlHorarios;
use Session;
use Redirect;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class checkinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function entradas($personal,$hoy)
     {
        $entradas = mdlChecadas::where([
            ['id_tblPersonal', '=', $personal],
            ['checada', '=', 0],
            ['fecha', '=', $hoy],
        ])->orWhere([
            ['id_tblPersonal', '=', $personal],
            ['checada', '=', 1],
            ['fecha', '=', $hoy],
        ])->orWhere([
            ['id_tblPersonal', '=', $personal],
            ['checada', '=', 2],
            ['fecha', '=', $hoy],
        ])->orWhere([
            ['id_tblPersonal', '=', $personal],
            ['checada', '=', 3],
            ['fecha', '=', $hoy],
        ])->orWhere([
            ['id_tblPersonal', '=', $personal],
            ['checada', '=', 5],
            ['fecha', '=', $hoy],
        ])->get();

        return $entradas;
     }
    

    public function store(Request $request)
    { 
        $msg = "";
        $personal = mdlPersonal::where('expediente', '=', $request['id_tblPersonal'])->get(); 
        if($personal->count()==0){
            $msg="NO EXISTE ESTE USUARIO";
            return $msg;     
        }
        if($personal[0]->modulo != 1){

            $nombre = $personal[0]->nombre;
            $dia=date("N");
            $hoy = date("Y-m-d");
            
            $horarios = mdlHorarios::where([
                ['id_tblPersonal', '=', $personal[0]->id],
                ['dia', '=', $dia],
            ])->get();

            $horarios_count = count($horarios);
            $hora = date('H:i:s');
            $hora_checada = Carbon::parse($hora)->hour;  
            $minuto_checada = Carbon::parse($hora)->minute;   
            

            if($horarios_count>0){    // este es el primer horario 
                if($horarios_count==1){ //un solo horario

                    $hora_entrada = Carbon::parse($horarios[0]->hora_entrada)->hour;
                    $hora_salida = Carbon::parse($horarios[0]->hora_salida)->hour; 
                    return $this->registroUnHorario($nombre,$personal[0]->id, $hora, $hora_entrada,
                    $hora_salida, $hora_checada, $minuto_checada, $checada="0", $hoy);   
                }
                if($horarios_count>1)
                {//varios horarios

                    $entradas = $this->entradas($personal[0]->id,$hoy)->count();

                    $salidas = mdlChecadas::where([
                        ['id_tblPersonal', '=', $personal[0]->id],
                        ['hora_Salida', '!=', null],
                        ['fecha', '=', $hoy],  
                    ])->count();

                    $faltas = mdlChecadas::where([
                        ['id_tblPersonal', '=', $personal[0]->id],
                        ['checada', '=', 11],
                        ['fecha', '=', $hoy],
                    ])->get();

                    $falta=$faltas->count();

                    if($falta!=0)
                    {
                        $msg = "TIENE UN PERMISO POR HORA NO ATENDIDO";
                        return $msg;     

                    }else{
                        if($entradas==$salidas){
                            $horasOmitidas=' ';
                            $count=0;
                            $entradasContempladas=$entradas;
                            foreach($horarios as $hor)
                            {
                                if($entradasContempladas==$count)
                                {  
                                    if($hora_checada>$hor->hora_salida)
                                    {
                                        $entradasContempladas=$entradasContempladas+1; 
                                        $horasOmitidas=$horasOmitidas.' - '.$hor->hora_entrada.' - ';

                                        $mdlChecadas = new mdlChecadas();
                                        $mdlChecadas->id_tblPersonal = $personal[0]->id;
                                        $mdlChecadas->hora = $hor->hora_entrada;
                                        $mdlChecadas->hora_salida = $hor->hora_salida;
                                        $mdlChecadas->checada = '5'; 
                                        $mdlChecadas->checada_salida = '5'; 
                                        $mdlChecadas->comentario = '';
                                        $mdlChecadas->fecha = $hoy; 
                                        $mdlChecadas->save();
                                    }
                                }
                                $count=$count+1;
                            }

                            $turno = 1;
                            $var = $this->entradas($personal[0]->id,$hoy)->count();
                            $salidas = mdlChecadas::where([
                                ['id_tblPersonal', '=', $personal[0]->id],
                                ['hora_Salida', '!=', null],
                                ['fecha', '=', $hoy],  
                            ])->count();
                            if($horarios_count>$salidas)
                            {
                                $hora_entrada = Carbon::parse($horarios[$var]->hora_entrada)->hour;
                                $hora_salida = Carbon::parse($horarios[$var]->hora_salida)->hour; 
                                
                                return $this->registroVariosHorario($nombre,$personal[0]->id, $hora, $hora_entrada,
                                $hora_salida, $hora_checada, $minuto_checada, $checada="0", $hoy,0,$turno,$var);  
                            }else{
                                $msg = 'Último horario '.$horarios[$var-1]->hora_entrada.'-'.$horarios[$var-1]->hora_salida;
                                return $msg;
                            }
                            
                        } else {

                            $turno = 2;
                            $var = $entradas - 1;
                            $hora_entrada = Carbon::parse($horarios[$var]->hora_entrada)->hour;
                            $hora_salida = Carbon::parse($horarios[$var]->hora_salida)->hour; 
        
                            return $this->registroVariosHorario($nombre,$personal[0]->id, $hora, $hora_entrada,
                            $hora_salida, $hora_checada, $minuto_checada, $checada="0", $hoy,1,$turno,$var);
                        }
                    }
                }
            } else {
                $msg = "NO TIENE HORARIOS";
                return $msg;
            }     
        }
        else {
            $msg = "NO ES UN USUARIO ACTIVO";
            return $msg;
        }
        
    }
     
    public function registroVariosHorario($nombre,$personal, $hora, $hora_entrada, $hora_salida,
     $hora_checada, $minuto_checada, $checada="0", $fecha,$solosalida=0,$turno,$var){
   
        if($turno==1){
            $mdlChecadas = new mdlChecadas();
            $mdlChecadas->id_tblPersonal = $personal;
            $mdlChecadas->hora = $hora;
            $mdlChecadas->comentario = ' ';
            $mdlChecadas->fecha = $fecha; 
            //$mdlChecadas->turno = $turno; 

            if($hora_checada<$hora_entrada){
                $mdlChecadas->checada = '0'; 
                $msg='ENTRADA CON BONO ';
            } elseif($hora_checada==$hora_entrada){
                if($minuto_checada==0){
                    $mdlChecadas->checada = '0';    //Entrada con bono
                    $msg='ENTRADA CON BONO ';
                } elseif($minuto_checada>0 && $minuto_checada<16){
                    $mdlChecadas->checada = '1';   // Entrada normal
                    $msg='ENTRADA NORMAL ';
                } elseif ($minuto_checada>15 && $minuto_checada<31) {
                    $mdlChecadas->checada = '2';   //Retardo
                    $msg='RETARDO ';
                } elseif ($minuto_checada>30) {
                    $mdlChecadas->checada = '3';    //FALTA
                    $msg='FALTA ';
                }
            } elseif($hora_checada>$hora_entrada){
                 $mdlChecadas->checada = '3';   //FALTA
                 $msg='FALTA '.$nombre;
            }
            
            $mdlChecadas->save();
            return $msg;     

        } else {  
            $entrada = $this->entradas($personal,$fecha);
    
            $estado='';
            $check='';

            if($hora_checada<$hora_salida){ 
                $estado='ANTICIPADA';
                $check='2';
            } 
            elseif($hora_checada>=$hora_salida) {
                $estado='NORMAL';
                $check='1';
            }
            return $this->registrarSalida($entrada,$hora,$estado,$check,$hora_entrada,
             $hora_salida,$nombre,$var);
        }
    }
    public function registroUnHorario($nombre,$personal, $hora, $hora_entrada,
     $hora_salida, $hora_checada, $minuto_checada, $checada="0", $fecha){

        $registros = mdlChecadas::where([
            ['id_tblPersonal', '=', $personal],
            ['fecha', '=', $fecha],
        ])->count();
        
        $hoy = date("Y-m-d");

        if($registros==0){
            $mdlChecadas = new mdlChecadas();
            $mdlChecadas->id_tblPersonal = $personal;
            $mdlChecadas->hora = $hora;
            $mdlChecadas->comentario = '';
            $mdlChecadas->fecha = $fecha;   

            if($hora_checada<$hora_entrada)
            {
                //Puede ser cambiado a entrada con bono
                $mdlChecadas->checada = '0'; 
                $msg='ENTRADA CON BONO ';
            }
            elseif($hora_checada==$hora_entrada)
            {
                if($minuto_checada==0){
                    $mdlChecadas->checada = '0';    //Entrada con bono
                    $msg='ENTRADA CON BONO';
                } elseif($minuto_checada>0 && $minuto_checada<16){
                    $mdlChecadas->checada = '1';   // Entrada normal
                    $msg='ENTRADA NORMAL';
                } elseif ($minuto_checada>15 && $minuto_checada<31) {
                    $mdlChecadas->checada = '2';   //Retardo
                    $msg='RETARDO';
                } elseif ($minuto_checada>30) {
                    $mdlChecadas->checada = '3';    //FALTA POR TIEMPO
                    $msg='FALTA';
                }
            }elseif($hora_checada>$hora_entrada)
            {
                $mdlChecadas->checada = '5';   //FALTA POR OMISION
                $msg='FALTA POR OMISION';
            }
            $mdlChecadas->save();
            //return 
            return $msg;      
        } 
        else 
        {
            $entrada = $this->entradas($personal,$fecha);
    
            $estado='';
            $check='';

            if($hora_checada<$hora_salida){ 
                $estado='ANTICIPADA';
                $check='2';
            } 
            elseif($hora_checada>=$hora_salida) {
                $estado='NORMAL';
                $check='1';
            }
            return $this->registrarSalidaUno($entrada,$hora,$estado,$check,$hora_entrada,
             $hora_salida,$nombre,$var=0);
        }

    }

    public function registrarSalida($entrada,$hora,$estado,$check,$hora_entrada,
     $hora_salida,$nombre,$var){
        $entrada_count = $entrada->count();
        

        $h1 = new \Carbon\Carbon($entrada[$var]->hora);
        $h2 = new \Carbon\Carbon($hora);
        $tolerancia=$h1->diffInMinutes($h2);  
        
        if($entrada_count>0)
        {
            if($entrada[$var]->checada_salida==null)
            {
                if($tolerancia<5)
                {
                    $msg='YA HA SIDO REGISTRADO ESPERE 5 MINUTOS - TRANSCURRIDO: '.$tolerancia.' min';
                    return $msg; 
                }else
                {
                    $entrada[$var]->checada_salida = $check;
                    $entrada[$var]->hora_salida = $hora;
                    $entrada[$var]->save();
                    $msg='SALIDA '.$estado;
                    return $msg; 
                }
            }else{
                $msg='YA ESTÁ REGISADA UNA SALIDA A ESTE HORARIO: '
                .$hora_entrada.':00 - '.$hora_salida.':00 ';
            return $msg;
            } 
        }else{
            $msg='NO HAY REGISTROS DE ENTRADA';
            return $msg;
        }
    }
    public function registrarSalidaUno($entrada,$hora,$estado,$check,$hora_entrada,
     $hora_salida,$nombre,$var){
        $entrada_count = $entrada->count();
        

        $h1 = new \Carbon\Carbon($entrada[$var]->hora);
        $h2 = new \Carbon\Carbon($hora);
        $tolerancia=$h1->diffInMinutes($h2);  
        
        if($entrada_count>0)
        {
            if($entrada[$var]->checada_salida==null)
            {
                if($tolerancia<5)
                {
                    $msg='YA HA SIDO REGISTRADO ESPERE 5 MINUTOS - TRANSCURRIDO: '.$tolerancia.' min';
                    return $msg; 
                }else
                {
                    $entrada[$var]->checada_salida = $check;
                    $entrada[$var]->hora_salida = $hora;
                    $entrada[$var]->save();
                    $msg='SALIDA '.$estado;
                    return $msg; 
                }
            }else{
                $h3 = new \Carbon\Carbon($entrada[$var]->hora_salida);
                $h4 = new \Carbon\Carbon($hora);
                $tolerancia2=$h3->diffInMinutes($h4);  
                if($tolerancia2<5)
                {
                    $msg='YA HA SIDO REGISTRADO ESPERE 5 MINUTOS - TRANSCURRIDO: '.$tolerancia2.' min';
                    return $msg; 
                }else
                {
                    $entrada[$var]->checada_salida = $check;
                    $entrada[$var]->hora_salida = $hora;
                    $entrada[$var]->save();
                    $msg='SALIDA '.$estado;
                    return $msg; 
                }
            } 
        }else{
            $msg='NO HAY REGISTROS DE ENTRADA';
            return $msg;
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
