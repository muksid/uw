<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwJurKatmFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_jur_katm_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('jur_clients_id');
            $table->integer('jur_katm_id');
            $table->string('file_hash');
            $table->string('file_name');
            $table->string('file_extension');
            $table->integer('file_size');
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
        Schema::dropIfExists('uw_jur_katm_files');
    }
}
