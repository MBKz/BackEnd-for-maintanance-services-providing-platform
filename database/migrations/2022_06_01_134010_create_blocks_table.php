<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
<<<<<<< HEAD
            $table->integer('duraition');
=======
            $table->integer('duration');
>>>>>>> 567b9134d2f6cf06aa8ea94551dfa672561fc157
            $table->integer('service_provider_id')->unsigned();
            $table->foreign('service_provider_id')->references('id')->on('service_providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blocks');
    }
};
