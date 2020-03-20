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
            ])->orderBy('hora_entrada','ASC')->get();

            $horarios_count = count($horarios);
            $hora = date('H:i:s');
            $hora_checada = Carbon::parse($hora)->hour;  
            $minuto_checada = Carbon::parse($hora)->minute;   
            

            if($horarios_count>0){    // este es el primer horario 
                if($horarios_count==1){ //un solo horario
                    $permisos = mdlChecadas::where([
                        ['id_tblPersonal', '=', $personal[0]->id],
                        ['entradaHoras', '!=', null],
                        ['salidaHoras', '=', null],
                        ['fecha', '=', $hoy],
                    ])->get();

                    if($permisos->count()!=0)
                    {
                        $msg = "TIENE UN PERMISO POR HORA NO ATENDIDO";
                        return $msg;     

                    }else{
                        $hora_entrada = Carbon::parse($horarios[0]->hora_entrada)->hour;
                        $hora_salida = Carbon::parse($horarios[0]->hora_salida)->hour; 
                        return $this->registroUnHorario($nombre,$personal[0]->id, $hora, $hora_entrada,
                        $hora_salida, $hora_checada, $minuto_checada, $checada="0", $hoy,$horarios); 
                    }  
                }
                if($horarios_count>1)
                {//varios horarios

                    $entradas = $this->entradas($personal[0]->id,$hoy)->count();

                    $salidas = mdlChecadas::where([
                        ['id_tblPersonal', '=', $personal[0]->id],
                        ['hora_Salida', '!=', null],
                        ['fecha', '=', $hoy],  
                    ])->count();

                    $permisos = mdlChecadas::where([
                        ['id_tblPersonal', '=', $personal[0]->id],
                        ['entradaHoras', '!=', null],
                        ['salidaHoras', '=', null],
                        ['fecha', '=', $hoy],
                    ])->get();

                    if($permisos->count()!=0)
                    {
                        $msg = "TIENE UN PERMISO POR HORA NO ATENDIDO";
                        return $msg;     

                    }else{
                        if($entradas==$salidas){
                        
                            $count=0;
                            $entradasContempladas=$entradas;
                            foreach($horarios as $hor)
                            {
                                if($entradasContempladas==$count)
                                {  
                                    if($hora>$hor->hora_salida)
                                    {
                                        $entradasContempladas=$entradasContempladas+1; 

                                        $mdlChecadas = new mdlChecadas();
                                        $mdlChecadas->id_tblPersonal = $personal[0]->id;
                                        $mdlChecadas->hora = $hor->hora_entrada;
                                        $mdlChecadas->hora_salida = $hor->hora_salida;
                                        $mdlChecadas->checada = '5'; 
                                        $mdlChecadas->checada_salida = '5'; 
                                        $mdlChecadas->comentario = '';
                                        $mdlChecadas->fecha = $hoy; 
                                        $mdlChecadas->save();
                                        //return $this->checada($personal[0]->id,$hor->hora_entrada,$com=null,$hoy,'5',$hor->hora_salida,'5',$msg);  
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
                                
                                $hora_entrada2 = Carbon::parse($horarios[$var]->hora_entrada);
                                $hora_salida2 = Carbon::parse($horarios[$var]->hora_salida); 
                                $entrada = $this->entradas($personal[0]->id,$hoy);
                                if($var>0){
                                    $hora_salida3 = Carbon::parse($horarios[$var-1]->hora_salida); 
                                    $h1 = new \Carbon\Carbon($hora_salida3);
                                    $h2 = new \Carbon\Carbon($hora);
                                    $diferencia=$h1->diffInMinutes($h2);
                                    $sal;

                                    if($h2>$h1){
                                        $sal = $diferencia;
                                    }else{
                                        $sal = $diferencia-($diferencia*2);
                                    }
                                    if($sal<30){
                                        if($sal<0){
                                            $entrada[$var-1]->checada_salida = '2';
                                            $msg='SALIDA ANTICIPADA';
                                        }else{
                                            $entrada[$var-1]->checada_salida = '1';
                                            $msg='SALIDA NORMAL';
                                        }
                                        
                                        $entrada[$var-1]->hora_salida = $hora;
                                        $entrada[$var-1]->save();
                                        
                                        return $msg; 
                                    }
                                }
                                
                                return $this->registroVariosHorario($nombre,$personal[0]->id, $hora, $hora_entrada2,
                                $hora_salida2, $hora_checada, $minuto_checada, $checada="0", $hoy,0,$turno,$var);  
                            }else{
                                $hora_salida3 = Carbon::parse($horarios[$var-1]->hora_salida); 
                                $h1 = new \Carbon\Carbon($hora_salida3);
                                $h2 = new \Carbon\Carbon($hora);
                                $diferencia=$h1->diffInMinutes($h2);
                                $sal;
                                $entrada = $this->entradas($personal[0]->id,$hoy);
                                if($h2>$h1){
                                    $sal = $diferencia;
                                }else{
                                    $sal = $diferencia-($diferencia*2);
                                }
                                if($sal<30){
                                    if($sal<0){
                                        $entrada[$var-1]->checada_salida = '2';
                                        $msg='SALIDA ANTICIPADA AL ULTIMO HORARIO';
                                    }else{
                                        $entrada[$var-1]->checada_salida = '1';
                                        $msg='SALIDA NORMAL AL ULTIMO HORARIO';
                                    }
                                    
                                    $entrada[$var-1]->hora_salida = $hora;
                                    $entrada[$var-1]->save();
                                    
                                    return $msg; 
                                }
                            }
                            
                        } else {

                            $turno = 2;
                            $var = $entradas - 1;
                            $hora_entrada = Carbon::parse($horarios[$var]->hora_entrada);
                            $hora_salida = Carbon::parse($horarios[$var]->hora_salida); 
        
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
        
        $check='0';
        $h1 = new \Carbon\Carbon($hora_entrada);
        $h2 = new \Carbon\Carbon($hora);
        $diferencia=$hora_entrada->diffInMinutes($h2);
        $dif;  
        if($h1<$h2){
            $dif = $diferencia;
        }else{
            $dif = $diferencia-($diferencia*2);
        }
        
        if($turno==1){
            
            if($hora>$hora_salida){
                return $this->checada($personal,$hora,$com=null,$fecha,$check='5',$horasalida=$hora,$checadasalida='5',$msg='HORA DE ENTRADA NO ATENDIDA');
            }
            if($dif<=0)
            {
                if($dif<-30)
                {
                    $hor = Carbon::parse($hora_entrada)->hour;
                    $min = Carbon::parse($hora_entrada)->minute;
                    return "TU TURNO EMPIEZA LAS ".$hor.":".$min." DEBES ESPERAR UN POCO MAS";
                }
                //Puede ser cambiado a entrada con bono
                $check = '0'; 
                $msg='ENTRADA CON BONO ';
            }
            else
            {
                if($dif>0&&$dif<16)
                {
                    $check = '1';   // Entrada normal
                    $msg='ENTRADA NORMAL';
                } elseif ($dif>15&&$dif<31) 
                {
                    $check = '2';   //Retardo
                    $msg='RETARDO';
                } elseif ($dif>30) 
                {
                    $check = '3';    //FALTA POR TIEMPO
                    $msg='FALTA';
                }
            }
            
            return $this->checada($personal,$hora,$com=null,$fecha,$check,$horasalida=null,$checadasalida=null,$msg);   

        } else {  
            $entrada = $this->entradas($personal,$fecha);
            $dia=date("N");
            $horarios = mdlHorarios::where([
                ['id_tblPersonal', '=', $personal],
                ['dia', '=', $dia],
            ])->get();
            $estado='';
            $check='';

            $h = Carbon::parse($horarios[$var]->hora_salida);
            $h = $h->format('H:i:s');

            if($hora<$h){ 
                $estado='ANTICIPADA';
                $check='2';
            } 
            elseif($hora>=$h) {
                $estado='NORMAL';
                $check='1';
            }
            return $this->registrarSalida($entrada,$hora,$estado,$check,$hora_entrada,
             $hora_salida,$nombre,$var);
        }
    }
    public function checada($id,$hora,$com,$fecha,$checada,$horasalida,$checadasalida,$msg){
        $mdlChecadas = new mdlChecadas();
        $mdlChecadas->id_tblPersonal = $id;
        $mdlChecadas->hora = $hora;
        $mdlChecadas->checada = $checada;
        $mdlChecadas->hora_salida = $horasalida;
        $mdlChecadas->checada_salida = $checadasalida;
        $mdlChecadas->comentario = $com;
        $mdlChecadas->fecha = $fecha;   
        $mdlChecadas->save();
        return $msg;
    }
    public function registroUnHorario($nombre,$personal, $hora, $hora_entrada,
     $hora_salida, $hora_checada, $minuto_checada, $checada="0", $fecha,$horarios){

        $registros = mdlChecadas::where([
            ['id_tblPersonal', '=', $personal],
            ['fecha', '=', $fecha],
        ])->count();
        
        $check='0';
        $h1 = new \Carbon\Carbon($horarios[0]->hora_entrada);
        $h2 = new \Carbon\Carbon($hora);
        $diferencia=$h1->diffInMinutes($h2);
        $dif;  
        if($h1<$h2){
            $dif = $diferencia;
        }else{
            $dif = $diferencia-($diferencia*2);
        }

        
        if($registros==0)
        {
            if($hora>$horarios[0]->hora_salida){
                return $this->checada($personal,$hora,$com=null,$fecha,$check='5',$horasalida=$hora,$checadasalida='5',$msg='HORA DE ENTRADA NO ATENDIDA');
            }
            if($dif<=0)
            {
                //Puede ser cambiado a entrada con bono
                $check = '0'; 
                $msg='ENTRADA CON BONO ';
            }
            else
            {
                if($dif>0&&$dif<16)
                {
                    $check = '1';   // Entrada normal
                    $msg='ENTRADA NORMAL';
                } elseif ($dif>15&&$dif<31) 
                {
                    $check = '2';   //Retardo
                    $msg='RETARDO';
                } elseif ($dif>30) 
                {
                    $check = '3';    //FALTA POR TIEMPO
                    $msg='FALTA';
                }
            }
            
            return $this->checada($personal,$hora,$com=null,$fecha,$check,$horasalida=null,$checadasalida=null,$msg);  
        } 
        else 
        {
            $entrada = $this->entradas($personal,$fecha);
            
            $estado='';
            $check='';
            $h12 = Carbon::parse($horarios[0]->hora_salida);
            $h12 = $h12->format('H:i:s');

            if($hora<$h12){ 
                $estado='ANTICIPADA';
                $check='2';

            } 
            else{
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
                $h3 = new \Carbon\Carbon($entrada[$var]->hora_salida);
                $h4 = new \Carbon\Carbon($hora);
                $tolerancia2=$h3->diffInMinutes($h4);  
                if($tolerancia2<5)
                {
                    $msg='YA HA SIDO REGISTRADO ESPERE 5 MINUTOS - TRANSCURRIDO: '.$tolerancia2.' min';
                    return $msg; 
                }else{
                    $horarios = mdlHorarios::where([
                        ['id_tblPersonal', '=', $personal[0]->id],
                        ['dia', '=', $dia],
                    ])->count();
                    $registros = mdlChecadas::where([
                        ['id_tblPersonal', '=', $personal],
                        ['fecha', '=', $fecha],
                    ])->count();

                    if($horarios==$registros){
                        $entrada[$var]->checada_salida = $check;
                        $entrada[$var]->hora_salida = $hora;
                        $entrada[$var]->save();
                        $msg='SALIDA '.$estado;
                        return $msg; 
                    }
                }
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
                    if($entrada[$var]->checada=='5'){
                        $entrada[$var]->checada_salida = '5';
                        $entrada[$var]->hora_salida = $hora;
                        $entrada[$var]->save();
                        $msg='ENTRADA NO ATENDIDA, HORA DE ENTRADA: '.$hora_entrada.':00';
                        return $msg; 
                    }
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

    public function permiso(Request $request)
    {
        $personal = mdlPersonal::where('expediente', '=', $request['id_tblPersonal'])->get(); 
        if($personal->count()==0)
        {
            return "NO EXISTE ESE USUARIO";     
        }

        $nombre = $personal[0]->nombre;
        $fecha = date("Y-m-d");
        $hora = date('H:i:s');

        $entrada = $this->entradas($personal[0]->id,$fecha);

        return $this->permisoPorHoras($entrada,$nombre,$personal[0]->id,$hora,$fecha); 
    }

    public function permisoPorHoras($entrada,$nombre,$personal,$hora,$fecha)
    {
        $entrada_count = $entrada->count();
        
        
        if($entrada_count>0)
        {
            if($entrada[0]->entradaHoras==null)
            {
                $entrada[0]->entradaHoras = $hora;
                $entrada[0]->save();
                return ' Comienzo de permiso por horas'; 
            }else{
                if($entrada[0]->salidaHoras==null)
                {
                    $entrada[0]->salidaHoras = $hora;
                    $entrada[0]->save();

                    $h1 = new \Carbon\Carbon($entrada[0]->entradaHoras);
                    $h2 = new \Carbon\Carbon($hora);
                    $diff=$h1->diffInMinutes($h2);  
                    $horasWork=$h1->diffInHours($h2);  

                    return 'PERMISO POR HORAS TOTAL: '.$diff.' Minutos - (Total en horas : '.$horasWork.')';
                }
                else{
                    $h1 = new \Carbon\Carbon($entrada[0]->entradaHoras);
                    $h2 = new \Carbon\Carbon($entrada[0]->salidaHoras);
                    $diff=$h1->diffInMinutes($h2);  
                    $horasWork=$h1->diffInHours($h2);
                    return 'PERMISO POR HORAS TOTAL: '.$diff.' Minutos - (Total en horas : '.$horasWork.')';
                }
            } 
        }else{
            return 'No hay registros de entrada';
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
