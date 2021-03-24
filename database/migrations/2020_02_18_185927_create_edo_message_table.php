<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdoMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edo_message', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('message_view');
            $table->integer('message_type');
            $table->string('from_name',512);
            $table->string('title',512);
            $table->text('text');
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
        Schema::dropIfExists('edo_message');
    }
}
