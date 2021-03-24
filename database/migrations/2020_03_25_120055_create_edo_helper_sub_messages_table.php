<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdoHelperSubMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edo_helper_sub_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('edo_message_id');
            $table->integer('edo_user_id');
            $table->integer('edo_message_journals_id');
            $table->integer('edo_type_message_id');
            $table->date('term_date');
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
        Schema::dropIfExists('edo_helper_sub_messages');
    }
}
