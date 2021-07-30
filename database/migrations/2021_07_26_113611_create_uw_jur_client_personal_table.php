<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwJurClientPersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_jur_client_personal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('jur_clients_id');
            $table->char('document_type', 2);
            $table->char('document_serial', 2);
            $table->string('document_number', 7);
            $table->date('document_date');
            $table->smallInteger('gender');
            $table->char('client_type');
            $table->date('birth_date');
            $table->char('document_region');
            $table->char('document_district');
            $table->smallInteger('resident');
            $table->string('family_name', 100);
            $table->string('name', 100);
            $table->string('patronymic', 100);
            $table->char('registration_region');
            $table->char('registration_district');
            $table->text('registration_address');
            $table->string('phone', 12);
            $table->string('pin', 14);
            $table->text('live_address');
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
        Schema::dropIfExists('uw_jur_client_personal');
    }
}
