<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CdCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cd_category', function (Blueprint $table) {
          $table->bigIncrements('cd_category_serial_id')->unsigned();
          $table->char('cd_category_uuid', 70)->unique();
          $table->string('cd_category_name')->nullable();
          $table->integer('created_by')->nullable();
          $table->integer('modified_by')->nullable();
          $table->dateTime('date_created')->nullable();
          $table->dateTime('date_modified')->nullable();
          $table->tinyInteger('deleted')->index()->unsigned()->default('0')->nullable()->comment('0 => Not Deleted, 1=> Deleted'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cd_category');
    }
}
