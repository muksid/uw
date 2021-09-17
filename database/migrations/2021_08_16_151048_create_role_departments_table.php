<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('code', 10);
            $table->char('parent_code', 10);
            $table->char('filial_code', 5);
            $table->smallInteger('lev');
            $table->integer('order_by')->nullable();
            $table->char('isActive', 2)->nullable();
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
        Schema::dropIfExists('role_departments');
    }
}
