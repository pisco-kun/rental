<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cd', function (Blueprint $table) {
          $table->bigIncrements('cd_serial_id')->unsigned();
          $table->char('cd_uuid', 70)->unique();
          $table->string('cd_title')->nullable();
          $table->integer('cd_rate')->nullable();
          $table->integer('cd_category')->nullable();
          $table->integer('cd_quantity')->nullable();
          $table->integer('created_by')->nullable();
          $table->integer('modified_by')->nullable();
          $table->dateTime('date_created')->nullable();
          $table->dateTime('date_modified')->nullable();
          $table->integer('deleted')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
