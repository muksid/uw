<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwPhyKatmFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_phy_katm_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uw_clients_id');
            $table->integer('uw_katm_id');
            $table->string('file_path');
            $table->string('file_hash', 100);
            $table->string('file_type', 10);
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
        Schema::dropIfExists('uw_phy_katm_files');
    }
}
