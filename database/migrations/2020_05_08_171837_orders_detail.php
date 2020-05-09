<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrdersDetail extends Migration
{
    /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('orders_detail', function (Blueprint $table) {
      $table->bigIncrements('orders_detail_serial_id')->unsigned();
      $table->char('orders_detail_uuid', 70)->unique();
      $table->integer('orders_serial_id')->nullable();
      $table->integer('cd_serial_id')->nullable();
      $table->integer('orders_detail_quantity')->nullable();
      $table->integer('orders_detail_rate')->nullable();
      $table->integer('orders_detail_days')->nullable();
      $table->dateTime('orders_detail_start_date')->nullable();
      $table->dateTime('orders_detail_end_date')->nullable();
      $table->integer('orders_late_days')->nullable();
      $table->dateTime('orders_late_date')->nullable();
      $table->integer('orders_late_charge')->nullable();
      $table->integer('orders_detail_return')->nullable()->comment('0 => Orders Not Returns, 1=> Orders Returns'); ;
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('orders_detail');
  }
}
