<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblChecadas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_tblPersonal');
            $table->time('hora')->nullable();
            $table->time('hora_salida')->nullable();
            $table->integer('checada'); 
            //1.-asistencia 2.-retardo 3.-inasistencia 
            //4.-incapacidad 5.-omisión de checada 
            //6.-canje de tiempo extra
            //7.-día económico(permiso) 8.-comisión
            $table->integer('checada_salida')->nullable();
            //1.-salida normal 2.-salida anticipada 5.-omision de checada
            $table->string('comentario',200)->nullable();
            $table->date('fecha')->nullable();           
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->foreign('id_tblPersonal')->references('id')->on('tblPersonal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblChecadas');
    }
}
