<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdoMessageJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edo_message_journal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('edo_message_journal_id');
            $table->integer('edo_message_id');
            $table->integer('in_number');
            $table->date('in_date');
            $table->integer('out_number');
            $table->date('out_date');
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
        Schema::dropIfExists('edo_message_journal');
    }
}
