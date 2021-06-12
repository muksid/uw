<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwLoanBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_loan_banks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('loan_types_id');
            $table->integer('filials_id');
            $table->smallInteger('isActive');
            $table->dateTime('startDate')->nullable();
            $table->dateTime('endDate')->nullable();
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
        Schema::dropIfExists('uw_loan_banks');
    }
}
