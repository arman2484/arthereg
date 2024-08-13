<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id');
      $table->integer('order_id')->unique();
      $table->integer('total_item');
      $table->integer('address_id');
      $table->integer('coupon_id');
      $table->string('payment_mode');
      $table->integer('order_status');
      $table->string('total_amount');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('order_items');
  }
};
