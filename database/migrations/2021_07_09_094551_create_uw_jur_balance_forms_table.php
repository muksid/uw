<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwJurBalanceFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_jur_balance_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uw_jur_clients_id');
            $table->integer('year');
            $table->smallInteger('quarter');
            $table->integer('ns10_code');
            $table->integer('ns11_code');
            $table->string('tin', 9);
            $table->string('company_name');
            $table->smallInteger('isActive');
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
        Schema::dropIfExists('uw_jur_balance_forms');
    }
}
