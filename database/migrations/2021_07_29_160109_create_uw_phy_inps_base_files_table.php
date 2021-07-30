<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwPhyInpsBaseFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_phy_inps_base_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uw_clients_id');
            $table->text('base_file');
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
        Schema::dropIfExists('uw_phy_inps_base_files');
    }
}
