<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwJurKatmClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_jur_katm_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('jur_clients_id');
            $table->string('claim_id');
            $table->integer('summa');
            $table->integer('scoring_ball');
            $table->text('json_data');
            $table->smallInteger('status');
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
        Schema::dropIfExists('uw_jur_katm_clients');
    }
}
