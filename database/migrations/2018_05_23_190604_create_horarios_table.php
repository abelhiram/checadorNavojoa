<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblHorarios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_tblPersonal');
            $table->integer('dia');
            $table->time('hora_entrada');
            $table->time('hora_salida');
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
        Schema::dropIfExists('tblHorarios');
    }
}
