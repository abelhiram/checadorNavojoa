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

class reportesController extends Controller
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
    public static function faltas($args, $opcion,$f1,$f2){

        $fechaInicio=$f1;
        $fechaFinal=$f2;
        $count=0;
        $i=0;
        $checadas = \App\mdlChecadas::orderBy('id','ASC')
        ->where('checada', '=', $opcion)
        ->where('id_tblPersonal', '=', $args)
        ->whereBetween('fecha',[$fechaInicio,$fechaFinal])
        ->get();
        
        foreach($checadas as $ch){
            if($checadas[0]->id==$ch->id){
                $count=$count+1;
            }
            else{
                if($ch->fecha==$checadas[$i-1]->fecha){

                }else{
                    $count=$count+1;
                }
            }
            $i=$i+1;
        }

        return $count;

        //revisar
        /*
            Si la opcion es Retardo, primero debe revisar la salida anticipada, si hay retardo no debe ponerlo, si hay salida anticipada
            Las salidas anticipadas (9) tambien son inasistencias (quiere decir que salio antes)
        */
        
    }
    public static function faltas2($args, $opcion,$f1,$f2){

        $fechaInicio=$f1;
        $fechaFinal=$f2;
        $count=0;
        $i=0;
        $checadas = \App\mdlChecadas::orderBy('id','ASC')
        ->where('checada_salida', '=', $opcion)
        ->where('id_tblPersonal', '=', $args)
        ->whereBetween('fecha',[$fechaInicio,$fechaFinal])
        ->get();
        
        foreach($checadas as $ch){
            if($checadas[0]->id==$ch->id){
                $count=$count+1;
            }
            else{
                if($ch->fecha==$checadas[$i-1]->fecha){

                }else{
                    $count=$count+1;
                }
            }
            $i=$i+1;
        }

        return $count;

        //revisar
        /*
            Si la opcion es Retardo, primero debe revisar la salida anticipada, si hay retardo no debe ponerlo, si hay salida anticipada
            Las salidas anticipadas (9) tambien son inasistencias (quiere decir que salio antes)
        */
        
    }
    public static function falts($args,$f1,$f2){

        $fechaInicio=$f1;
        $fechaFinal=$f2;
        
        $checadas = \App\mdlChecadas::orderBy('id','desc')
        ->where('id_tblPersonal', '=', $args)
        ->whereBetween('fecha',[$fechaInicio,$fechaFinal])
        ->get();


        return $checadas;

    }
    public static function bono($args,$f1,$f2){

        $fechaInicio=$f1;
        $fechaFinal=$f2;
        $bono=0;
        $checadas = \App\mdlChecadas::orderBy('id','desc')
        ->where('id_tblPersonal', '=', $args)
        ->whereBetween('fecha',[$fechaInicio,$fechaFinal])
        ->get();
        foreach($checadas as $ch){
            if($ch->checada==1 ||$ch->checada==3 || $ch->checada==2 || $ch->checada==5 || 
            $ch->checada_salida==2 || $ch->checada_salida==5){
                $bono = 1;
            }
        }
        


        return $bono;

    }
    public function index(Request $request)
    {

        $fechaInicio=$request->get('fechaInicio'); 

        $fechaFinal=$request->get('fechaFinal');    

        $nombre=$request->get('nombre'); 

        $expediente=$request->get('expediente');    

        $personal = \App\mdlPersonal::orderBy('modulo','ASC')->orderBy('nombre', 'ASC')
        ->nombre($nombre)
        ->expediente($expediente)
        ->paginate(100);

        return view('reportes',compact('personal'));
    }
    public function rep(Request $request)
    {

        $fechaInicio=$request->get('fechaInicio'); 

        $fechaFinal=$request->get('fechaFinal');    

        $nombre=$request->get('nombre'); 

        $expediente=$request->get('expediente');    

        $personal = \App\mdlPersonal::where('modulo', '=', 0)
        ->orwhere('modulo', '=', null)
        ->nombre($nombre)
        ->expediente($expediente)
        ->orderBy('nombre', 'ASC')
        ->paginate(100);

        return view('reportes/rep2',compact('personal'));
    }

    /**
     * Regresa la vista de reportesGenerales
     */
    public function reportesGenerales(Request $request){
        $nombre=$request->get('nombre'); 
        $expediente=$request->get('expediente'); 
        $personal = \App\mdlPersonal::orderBy('id','ASC')
        ->nombre($nombre)
        ->expediente($expediente)
        ->paginate(15);
        return view('reportes/reportesGenerales', compact('personal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function crear($id)
    {
        
        

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $personal = mdlPersonal::where('id', '=', $id)->take(1)->get(); 
        $horario = mdlHorarios::where('id_tblPersonal', '=', $id)->get(); 
        $checada = mdlChecadas::where('id_tblPersonal', '=', $id)
        ->where('modulo', '=', 0)
        ->orwhere('modulo', '=', null)
        ->paginate(7); 
        return view('reportes\reportes',compact('horario','personal','checada'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
