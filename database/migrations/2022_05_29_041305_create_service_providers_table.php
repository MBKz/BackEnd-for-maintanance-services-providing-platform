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
        Schema::create('service_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->double('rate')->nullable();
            $table->integer('num_of_raters')->nullable();
            $table->string('device_token')->nullable();
            $table->integer('user_id')->unsigned()->unique();
            $table->integer('job_id')->unsigned();
            $table->integer('account_status_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->integer('identity_id')->unsigned()->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('account_status_id')->references('id')->on('accounts_status')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('identity_id')->references('id')->on('identities')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_providers');
    }
};
