<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwJurSaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_jur_saldos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('jur_clients_id');
            $table->string('client_name');
            $table->string('client_acc');
            $table->integer('credit');
            $table->integer('debit');
            $table->string('curr');
            $table->date('lead_last_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uw_jur_saldos');
    }
}
