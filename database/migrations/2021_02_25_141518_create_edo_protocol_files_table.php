<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdoProtocolFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edo_protocol_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('protocol_id');
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
        Schema::dropIfExists('edo_protocol_files');
    }
}
