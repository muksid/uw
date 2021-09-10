<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwStatusNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_status_names', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('name', 50);
            $table->char('name_ru', 50);
            $table->char('type', 5);
            $table->char('isActive', 1);
            $table->smallInteger('order');
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
        Schema::dropIfExists('uw_status_names');
    }
}
