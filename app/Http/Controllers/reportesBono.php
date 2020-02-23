<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\mdlPersonal;
use App\mdlHorarios;
use App\mdlChecadas;
use Session;
use Redirect;
use DB;
use Carbon\Carbon;

class reportesBono extends Controller
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
     *
     * @return \Illuminate\Http\Response
     */
public function bonos(Request $request){



        //revisar
        /*
            Si la opcion es Retardo, primero debe revisar la salida anticipada, si hay retardo no debe ponerlo, si hay salida anticipada
            Las salidas anticipadas (9) tambien son inasistencias (quiere decir que salio antes)
        */
        
    }

    
    public function index(Request $request)
    {

        $fechaInicio=$request->get('fechaInicio'); 
        $fechaFinal=$request->get('fechaFinal');

        $personal = \App\mdlPersonal::orderBy('id','ASC')->get();
        foreach($personal as $persona){
            $faltas = mdlchecadas::where([
                    ['id_tblPersonal', '=', $persona->id],
                    ['checada', '=', 3],
            ])->whereBetween('fecha',[$fechaInicio,$fechaFinal])->count();
           /* echo $persona->nombre." ".$faltas;
            echo "<br>";*/
            
            
            
            if($faltas==0){
                
               $checadas = mdlChecadas::where([
                    ['id_tblPersonal', '=', $persona->id],
                    ['checada', '=', 1],
                ])->orWhere([
                    ['id_tblPersonal', '=', $persona->id],
                    ['checada', '=', 2],
                ])->whereBetween('fecha',[$fechaInicio,$fechaFinal])->count();
           
                
                if($checadas==0){
                    $fechaInicio=$request->get('fechaInicio'); 

                    $fechaFinal=$request->get('fechaFinal');    

                    $nombre=$request->get('nombre'); 

                    $expediente=$request->get('expediente');    

                    $personal = \App\mdlPersonal::orderBy('id','ASC')
                    ->nombre($nombre)
                    ->expediente($expediente)
                    ->paginate(50);
                    //...
                            
                                /* echo $persona->id;
                                echo " ";
                                echo $persona->nombre;
                                echo "<br>"; */
                                

                                //vista
                    return view('reportesBono',compact('personal'));
                }

            
            }
        }
       
       return "";

    }
    
    
}