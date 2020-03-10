<?php

namespace App\Http\Controllers;
use App\mdlPersonal;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Image;

class personalController extends Controller
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
        $nombre=$request->get('nombre'); 
        $expediente=$request->get('expediente');    
        $personal = \App\mdlPersonal::orderBy('modulo','ASC')->orderBy('nombre', 'ASC')
        ->nombre($nombre)
        ->expediente($expediente)
        ->paginate(15);
        return view('index',compact('personal'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('personal.createPersonal');
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   

    public function store(Request $request)
    {

        $this->validate($request,[
            'expediente'=>'required|max:11|unique:tblPersonal',
            'nombre'=>'required|max:55',
            'email'=>'required|string|email|max:255',
        ]);

        $mdlPersonal = new mdlPersonal();

        if($request->file('foto')!=null){
        $extension = $request->file('foto')->getClientOriginalExtension();
        $file_name = $request['expediente'].'.'.$extension;
        Image::make($request->file('foto'))
            ->resize(144,144)
            ->save('img/usuarios/'.$file_name);
        }else{
            $extension='no';
        }

        $mdlPersonal->expediente = $request['expediente'];
        $mdlPersonal->nombre = $request['nombre'];
        $mdlPersonal->email = $request['email'];
        $mdlPersonal->nombramiento = $request['nombramiento'];
        $mdlPersonal->jornada = $request['jornada'];  
        $mdlPersonal->foto = $extension;
        $mdlPersonal->modulo = $request['modulo']; 
        $mdlPersonal->save();
        Session::flash('message','personal creado correctamente');
        return Redirect::to('/personal');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $mdlPersonal = mdlPersonal::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mdlPersonal = mdlPersonal::findOrFail($id);
        return view('personal.editPersonal',['mdlPersonal'=>$mdlPersonal]);
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
        $this->validate($request,[
            'expediente'=>'required|max:11',
            'nombre'=>'required|max:55',
            'email'=>'required|string|email|max:255',
        ]);
        if($request['seguridad']!='aceptar'){
            Session::flash('message','CLAVE DE SEGURIDAD INVÁLIDA');
            return back()->withInput();
        }else{
            $mdlPersonal = mdlPersonal::findOrFail($id);
            if($request->file('foto')!=null){
            $extension = $request->file('foto')->getClientOriginalExtension();
            $file_name = $request['expediente'].'.'.$extension;
            Image::make($request->file('foto'))
                ->resize(144,144)
                ->save('img/usuarios/'.$file_name);
            }else{
                $extension='no';
            }

            $mdlPersonal->foto = $extension;
            $mdlPersonal->fill($request->all());
            $mdlPersonal->save();

            Session::flash('message','PERSONAL ACTUALIZADO CORRECTAMENTE');
            return back();
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mdlPersonal = mdlPersonal::findOrFail($id);
        $mdlPersonal->foto='1';
        $mdlPersonal->save();
        Session::flash('message','personal desabilitado correctamente');
        return Redirect::to('/personal');
    }
}
