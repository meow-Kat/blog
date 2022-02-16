<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckFeatureColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('price')->constrained('users')->after('order_id');
        });
        // 要幫 user 增加 level
        Schema::table('users', function (Blueprint $table) {
            $table->integer('level')->default(1)->after('id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('price');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
}
