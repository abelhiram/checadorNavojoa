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


class checadasController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         
    }

    public function crear($id)
    {    
        $personal = mdlPersonal::where('id', '=', $id)->take(1)->get(); 
        if($personal->count()==0){
            Session::flash('message','Usuario inexistente');
            return Redirect::to('/personal');
        }
        $horario = mdlHorarios::where('id_tblPersonal', '=', $id)->get(); 
        $checada = mdlChecadas::where('id_tblPersonal', '=', $id)->get(); 
        return view('checadas.createChecada',compact('horario','personal','checada'));
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mdlChecadas = new mdlChecadas();
        
        $mdlChecadas->id_tblPersonal = $request->input('id_tblPersonal');
        $mdlChecadas->hora = $request->input('hora');  
        $mdlChecadas->hora_salida = $request->input('hora');  
        $mdlChecadas->checada = $request->input('checada') ;
        $mdlChecadas->checada_salida = '1';
        $mdlChecadas->comentario = 'PC';
        $mdlChecadas->fecha = $request->input('fecha'); 
        $mdlChecadas->save();
        Session::flash('message','Checada creada correctamente');
        //return 
        return Redirect::to('/checadas/'.$request['id_tblPersonal']);
  
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
        if($personal->count()==0){
            Session::flash('message','Usuario inexistente');
            return Redirect::to('/personal');
        }
        $horario = mdlHorarios::where('id_tblPersonal', '=', $id)->get(); 
        $checada = mdlChecadas::where('id_tblPersonal', '=', $id)->paginate(15); 
        return view('checadas/checadas',compact('horario','personal','checada'));
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mdlChecadas = mdlChecadas::find($id);
        if($mdlChecadas==null){
            return Redirect::to('/personal');
        }
        $personal = mdlPersonal::where('id', '=', $mdlChecadas->id_tblPersonal)->take(1)->get(); 
        return view('checadas.editChecadas',['mdlChecadas'=>$mdlChecadas],compact('personal'));

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
        $mdlChecadas = mdlChecadas::find($id);
        $mdlChecadas->fill($request->all());
        $mdlChecadas->save();

        Session::flash('message','Editado correctamente');

        return Redirect::to('/checadas/'.$request['id_tblPersonal']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $horario = mdlHorarios::find($id);
        $checada = mdlChecadas::find($id);
        mdlChecadas::destroy($id);
        Session::flash('message','Eliminado correctamente');
        return Redirect::to('/checadas/'.$checada->id_tblPersonal);
    }

    public function index(Request $request)
    {
        $fechaInicio=$request->get('fechaInicio'); 
        $fechaFinal=$request->get('fechaFinal'); 
        $id=$request->get('id');

        $personal = mdlPersonal::where('id', '=', $id)->take(1)->get(); 
        if($personal->count()==0){
            Session::flash('message','Usuario inexistente');
            return Redirect::to('/personal');
        }

        $checada = \App\mdlChecadas::orderBy('id','ASC')
        ->where('id_tblPersonal', '=', $id)
        ->whereBetween('fecha', [$fechaInicio, $fechaFinal])
        ->paginate(7);

        $horario = mdlHorarios::where('id_tblPersonal', '=', $id)->get(); 

        return view('checadas/checadas',compact('horario','personal','checada'));
    }

    public function fal()
    {   
        $personales = mdlPersonal::all();
        $hoy = new Carbon('Yesterday'); 
        $dia = $hoy->format('N');
        $hora = date('H:i:s');

        foreach($personales as $personal){
            $horarios = mdlHorarios::where([
                ['id_tblPersonal', '=', $personal->id],
                ['dia', '=', $dia],
            ])->get();

            if($horarios->count()>0){
                foreach($horarios as $horario){
                    
                    $entradas = mdlChecadas::where([
                        ['id_tblPersonal', '=', $personal->id],
                        ['hora', '!=', null],
                        ['fecha', '=', $hoy],
                    ])->get();
                    $omisiones = mdlChecadas::where([
                        ['id_tblPersonal', '=', $personal->id],
                        ['hora_salida', '=', null],
                        ['fecha', '=', $hoy],
                    ])->get();
    
                    if($horarios->count()>$entradas->count()){
                        $mdlChecadas = new mdlChecadas();
                        $mdlChecadas->id_tblPersonal = $personal->id;
                        $mdlChecadas->hora = $hora;
                        $mdlChecadas->hora_salida = $hora;
                        $mdlChecadas->checada = 5;
                        $mdlChecadas->checada_salida = 5;
                        $mdlChecadas->comentario = '';
                        $mdlChecadas->fecha = $hoy; 
                        $mdlChecadas->save();
                    }else{
                        foreach ($omisiones as $omision) {
                            $omision->hora_Salida=$hora;
                            $omision->checada_salida=5;
                            $omision->save();
                        }
                    }
                }
            }
        }
    }
}
