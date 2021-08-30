<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhyMyidClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phy_myid_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name', 50)->nullable();
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('pinfl', 14)->nullable();
            $table->string('inn', 9)->nullable();
            $table->string('gender', 2)->nullable();
            $table->string('birth_place', 100);
            $table->string('birth_country', 50);
            $table->date('birth_date');
            $table->string('nationality', 50)->nullable();
            $table->string('citizenship', 50)->nullable();
            $table->string('pass_data', 9);
            $table->string('issued_by');
            $table->date('issued_date');
            $table->date('expiry_date');
            $table->string('phone', 20)->nullable();
            $table->string('email', 20)->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('temporary_address')->nullable();
            $table->text('permanent_registration')->nullable();
            $table->text('temporary_registration')->nullable();
            $table->string('branch_code', 5);
            $table->integer('work_user_id');
            $table->char('isActive', 2);
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
        Schema::dropIfExists('phy_myid_clients');
    }
}
