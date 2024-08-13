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
      $table->integer('product_id');
      $table->integer('user_id');
      $table->integer('coupon_id');
      $table->integer('payment_id');
      $table->integer('address_id');
      $table->string('product_price');
      $table->string('payment_mode');
      $table->string('product_color');
      $table->string('product_size');
      $table->string('quantity');
      $table->integer('status');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
