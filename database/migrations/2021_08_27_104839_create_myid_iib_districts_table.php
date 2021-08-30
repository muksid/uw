<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMyidIibDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('myid_iib_districts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('code', 10);
            $table->char('name', 100);
            $table->char('name_ru', 100);
            $table->char('region_code', 10);
            $table->char('district_code', 10);
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
        Schema::dropIfExists('myid_iib_districts');
    }
}
