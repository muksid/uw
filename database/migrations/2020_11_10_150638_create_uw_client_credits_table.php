<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwClientCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_client_credits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uw_clients_id');
            $table->integer('claim_id');
            $table->integer('credit_security');
            $table->string('credit_security_name', '512');
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
        Schema::dropIfExists('uw_client_credits');
    }
}
