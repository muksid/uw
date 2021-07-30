<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUwJurClientCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_jur_client_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uw_jur_clients_id');
            $table->string('code', 10);
            $table->string('title', 255);
            $table->string('json_data', 255);
            $table->integer('user_id');
            $table->smallInteger('status')->default(1);
            $table->string('process_type', 5);
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
        Schema::dropIfExists('uw_jur_client_comments');
    }
}
