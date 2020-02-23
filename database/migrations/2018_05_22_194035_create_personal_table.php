<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblPersonal', function (Blueprint $table) {
            $table->increments('id');
            $table->string('expediente');
            $table->string('nombre',120);
            $table->string('email');
            $table->enum('nombramiento',['determinado','indeterminado']);
            $table->enum('jornada',['horas','tiempo completo','medio tiempo','confianza']);
            $table->binary('huella')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('modulo')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblPersonal');
    }
}
