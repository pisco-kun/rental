<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->bigIncrements('users_serial_id')->unsigned();
      $table->char('users_uuid', 70)->unique();
      $table->string('users_nik')->nullable();
      $table->string('users_name')->nullable();
      $table->string('users_phone')->nullable();
      $table->string('users_email')->unique();
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
    Schema::dropIfExists('users');
  }
}
