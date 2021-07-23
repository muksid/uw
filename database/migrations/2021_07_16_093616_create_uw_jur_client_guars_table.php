<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwJurClientGuarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_jur_client_guars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('jur_clients_id')->index();
            $table->string('guar_type', 10);
            $table->integer('title');
            $table->bigInteger('guar_sum');
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
        Schema::dropIfExists('uw_jur_client_guars');
    }
}
