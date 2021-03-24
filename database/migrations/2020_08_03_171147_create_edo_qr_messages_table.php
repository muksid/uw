<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdoQrMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edo_qr_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->date('message_date');
            $table->string('message_number');
            $table->string('title');
            $table->string('message_hash');
            $table->text('text');
            $table->integer('performer_user_id');
            $table->integer('guide_user_id');
            $table->date('signature_date');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('edo_qr_messages');
    }
}
