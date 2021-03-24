<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdoRedirectTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edo_redirect_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('from_guide_id');
            $table->integer('to_guide_id');
            $table->integer('edo_message_id');
            $table->tinyInteger('status');
            $table->text('redirect_desc');
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
        Schema::dropIfExists('edo_redirect_tasks');
    }
}
