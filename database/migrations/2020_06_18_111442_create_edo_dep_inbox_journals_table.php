<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdoDepInboxJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edo_dep_inbox_journals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('depart_id');
            $table->integer('director_id');
            $table->integer('user_id');
            $table->string('in_number', 25);
            $table->string('from_name', 512);
            $table->string('title', 512);
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
        Schema::dropIfExists('edo_dep_inbox_journals');
    }
}
