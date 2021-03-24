<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdoMessageSubUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edo_message_sub_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('edo_message_id');
            $table->integer('edo_mes_jrls_id');
            $table->integer('from_user_id');
            $table->integer('to_user_id');
            $table->integer('performer_user');
            $table->tinyInteger('is_read');
            $table->dateTime('read_date');
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
        Schema::dropIfExists('edo_message_sub_users');
    }
}
