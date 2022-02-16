<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersAndOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   // 建立這個表單可以拆開使用
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // 一對多關係關聯 user_id
            $table->foreignId('user_id')->constrained('users');
            // orders 會和 cart 產生關聯，一個購物車對應一個訂單
            $table->foreignId('cart_id')->constrained('carts');
            // 有沒有被處理
            $table->boolean('is_shipped')->default(0);
            $table->timestamps();
        });
        Schema::create('_order_items', function (Blueprint $table) {
            $table->id();
            // 每個 orderItem 對應一個 product，一對多關係，一個 product 會對應多個 oderItem
            $table->foreignId('product_id')->constrained('products');
            // 會屬於訂單
            $table->foreignId('order_id')->constrained('orders');
            $table->timestamps();
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
        Schema::dropIfExists('order_items');
    }
}
