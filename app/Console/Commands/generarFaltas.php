<?php

namespace App\Console\Commands;

use App\mdlChecadas;
use App\mdlPersonal;
use App\mdlHorarios;
use Carbon\Carbon;
use Session;
use Redirect;
use Illuminate\Console\Command;

class generarFaltas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:faltas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera registros de inasistencia por omisiones de checadas';

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
        $personales = mdlPersonal::all(); 
        $hoy = new Carbon('Yesterday'); 
        $dia = $hoy->format('N');
        $hora = date('H:i:s');

        foreach($personales as $personal)
        {
            if($personal->modulo!=1)
            {
                $horarios = mdlHorarios::where([
                    ['id_tblPersonal', '=', $personal->id],
                    ['dia', '=', $dia],
                ])->get();
    
                if($horarios->count()>0)
                {
                    foreach($horarios as $horario)
                    {
                        
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
        
                        if($horarios->count()>$entradas->count())
                        {
                            $mdlChecadas = new mdlChecadas();
                            $mdlChecadas->id_tblPersonal = $personal->id;
                            $mdlChecadas->checada = 5;
                            $mdlChecadas->checada_salida = 5;
                            $mdlChecadas->comentario = '';
                            $mdlChecadas->fecha = $hoy; 
                            $mdlChecadas->save();
                        }else
                        {
                            foreach ($omisiones as $omision) 
                            {
                                $omision->checada_salida=5;
                                $omision->save();
                            }
                        }
                    }
                }
            }
        }
    }
}
