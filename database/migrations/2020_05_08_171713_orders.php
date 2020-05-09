<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Orders extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('orders', function (Blueprint $table) {
      $table->bigIncrements('orders_serial_id')->unsigned();
      $table->integer('users_serial_id')->nullable();
      $table->char('orders_uuid', 70)->unique();
      $table->integer('orders_total')->nullable();
      $table->tinyInteger('orders_status')->default('0')->nullable()->comment('0 => Orders Progress, 1=> Orders Done'); 
      $table->dateTime('date_created')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('orders');
  }
}
