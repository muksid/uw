<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdoManagementMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edo_management_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('qr_name', 25);
            $table->string('qr_hash', 25);
            $table->string('qr_file', 100);
            $table->string('title', 255);
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
        Schema::dropIfExists('edo_management_members');
    }
}
