<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreteEdoManagementProtocolMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('edo_management_protocol_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('protocol_id');
            $table->integer('user_id');
            $table->tinyInteger('user_sort');
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
        //
        Schema::dropIfExists('edo_management_protocol_members');
    }
}
