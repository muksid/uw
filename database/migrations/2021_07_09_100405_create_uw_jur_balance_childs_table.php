<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwJurBalanceChildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_jur_balance_childs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uw_jur_balance_id');
            $table->integer('row_no');
            $table->decimal('sum_begin_period');
            $table->decimal('sum_end_period');
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
        Schema::dropIfExists('uw_jur_balance_childs');
    }
}
