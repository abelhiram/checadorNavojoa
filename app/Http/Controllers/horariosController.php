<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\mdlPersonal;
use App\mdlHorarios;
use Session;
use Redirect;
use DB;

class horariosController extends Controller
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
    public function index(Request $request)
    {

        $dia=$request->get('dia'); 
        $id=$request->get('id');

        $personal = mdlPersonal::where('id', '=', $id)->take(1)->get(); 
        if($personal->count()==0){
            Session::flash('message','Usuario inexistente');
            return Redirect::to('/personal');
        }

        $horario = \App\mdlHorarios::orderBy('id','ASC')
        ->where('id_tblPersonal', '=', $id)
        ->where('dia', '=', $dia)
        ->paginate(7);


        return view('horarios/horarios',compact('horario','personal'));
       
        //$horario = \App\mdlHorarios::all();
        //return view('horarios\horarios',compact('horario'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('horarios.createHorario');

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function crear($id)
    {
        $personal = mdlPersonal::where('id', '=', $id)->take(1)->get(); 
        if($personal->count()==0){
            Session::flash('message','Usuario inexistente');
            return Redirect::to('/personal');
        }
        $horario = mdlHorarios::where('id_tblPersonal', '=', $id)->get(); 
        return view('horarios.createHorario',compact('horario','personal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mdlHorarios = new mdlHorarios();
        $mdlHorarios->id_tblPersonal = $request['id_tblPersonal'];
        $mdlHorarios->dia = $request['dia'];
        $mdlHorarios->hora_entrada = $request['hora_entrada'];
        $mdlHorarios->hora_salida = $request['hora_salida'];
        $mdlHorarios->save();
        Session::flash('message','Horario creado correctamente');
        return Redirect::to('/horarios/'.$request['id_tblPersonal']);
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
        return view('horarios/horarios',compact('horario','personal'));

        /*
        ->join('tblpersonal', 'tblhorarios.id_tblPersonal', '=', 'tblpersonal.expediente')
        ->select('tblhorarios.*', 'tblpersonal.nombre')
        */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $horario = mdlHorarios::where('id_tblPersonal', '=', $id)->get(); 
        $mdlHorarios = mdlHorarios::find($id); 
        if($mdlHorarios==null){
            return Redirect::to('/personal');
        }
        $personal = mdlPersonal::where('id', '=', $mdlHorarios->id_tblPersonal)->take(1)->get(); 
        
        
        return view('horarios.editHorario',['mdlHorarios'=>$mdlHorarios],compact('personal','horario'));
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
        $mdlHorarios = mdlHorarios::find($id);
        $mdlHorarios->fill($request->all());
        $mdlHorarios->save();

        Session::flash('message','horario editado correctamente');

        return Redirect::to('/horarios/'.$request['id_tblPersonal']);
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
        mdlHorarios::destroy($id);
        Session::flash('message','horario eliminado correctamente');
        return Redirect::to('/horarios/'.$horario->id_tblPersonal);
    }
}
