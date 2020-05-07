<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Admin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
          $table->bigIncrements('admin_serial_id')->unsigned();
          $table->char('admin_uuid', 70)->unique();
          $table->string('admin_name')->nullable();
          $table->string('admin_email')->unique();
          $table->string('admin_password');
          $table->nullableTimestamps();
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
