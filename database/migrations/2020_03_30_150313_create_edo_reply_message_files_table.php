<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdoReplyMessageFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edo_reply_message_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('edo_reply_message_id');
            $table->string('file_hash',255);
            $table->string('file_name',255);
            $table->string('file_extension',25);
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
        Schema::dropIfExists('edo_reply_message_files');
    }
}
