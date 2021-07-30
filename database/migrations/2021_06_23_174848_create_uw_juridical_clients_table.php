<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwJuridicalClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_juridical_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('claim_id', 20);
            $table->date('claim_date');
            $table->string('inn', 9);
            $table->string('claim_number', 10);
            $table->string('agreement_number', 10);
            $table->date('agreement_date');
            $table->smallInteger('resident');
            $table->smallInteger('juridical_status');
            $table->string('nibbd', 10);
            $table->string('client_type', 10);
            $table->string('jur_name', 255);
            $table->string('live_address', 255);
            $table->string('owner_form', 5);
            $table->smallInteger('goverment');
            $table->string('registration_region', 5);
            $table->string('registration_district', 5);
            $table->string('registration_address', 255);
            $table->string('phone', 12);
            $table->string('hbranch', 10)->nullable();
            $table->string('oked', 10);
            $table->string('katm_sir', 50);
            $table->string('okpo', 10);
            $table->integer('summa');
            $table->string('client_code', 8);
            $table->integer('loan_type_id');
            $table->string('branch_code', 5);
            $table->string('local_code', 10);
            $table->integer('user_id');
            $table->smallInteger('status')->default(0);
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
        Schema::dropIfExists('uw_juridical_clients');
    }
}
