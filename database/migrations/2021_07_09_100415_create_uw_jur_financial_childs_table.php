<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwJurFinancialChildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_jur_financial_childs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uw_jur_financial_id');
            $table->integer('row_no');
            $table->decimal('sum_old_period_doxod');
            $table->decimal('sum_old_period_rasxod');
            $table->decimal('sum_period_doxod');
            $table->decimal('sum_period_rasxod');
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
        Schema::dropIfExists('uw_jur_financial_childs');
    }
}
