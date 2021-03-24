<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ip_networks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filial_code',10);
            $table->integer('ip_owner_id');
            $table->string('ip_first',25);
            $table->string('ip_second',25);
            $table->string('ip_route',25);
            $table->smallInteger('ip_net');
            $table->smallInteger('ip_status');
            $table->mediumText('ip_description');
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
        Schema::dropIfExists('ip_networks');
    }
}
